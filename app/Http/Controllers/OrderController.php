<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\CancelOrderRequest;
use App\Notifications\OrderClosedNotification;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isCustomer()) {
            $orders = Order::with('customer.user')
                ->where('customer_id', $user->id)
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        }

        if ($user->isEmployee()) {
            $orders = Order::with('customer.user')
                ->whereIn('status', ['pending', 'processing'])
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        }

        // Admin
        $query = Order::with('customer.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.tshirtImage', 'items.color']);

        return view('orders.show', compact('order'));
    }

    public function close(Order $order)
    {
        ini_set('memory_limit', '512M');
        $this->authorize('close', $order);

        $estadosParaFechar = ['pending', 'processing', 'paga', 'em_processamento', 'paid'];

        if (!in_array(strtolower(trim($order->status)), $estadosParaFechar)) {
            return back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'Esta encomenda não pode ser fechada no estado atual (' . $order->status . ').');
        }

        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);

        $directory = storage_path('app/private/pdf_receipts');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', base_path());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.receipt', compact('order'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->setOptions($options);
        $pdf->save($directory . '/receipt_' . $order->id . '.pdf');

        $order->status = 'closed';
        $order->save();

        $customerUser = $order->customer->user;
        if ($customerUser) {
            Notification::send($customerUser, new OrderClosedNotification($order));
        }

        return redirect()
            ->route('orders.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Encomenda #' . $order->id . ' fechada com sucesso! Recibo gerado e arquivado.');
    }

    public function cancel(CancelOrderRequest $request, Order $order)
    {
        $this->authorize('cancel', $order);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'Esta encomenda não pode ser anulada.');
        }

        $validated = $request->validated();

        $order->status = 'canceled';
        $order->reason_for_cancellation = $validated['reason_for_cancellation'] ?? null;
        $order->save();

        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Encomenda #' . $order->id . ' anulada com sucesso.');
    }

    public function downloadReceipt(Order $order)
    {
        $this->authorize('downloadReceipt', $order);

        if ($order->status !== 'closed') {
            abort(404, 'O recibo só está disponível para encomendas fechadas.');
        }

        $path = storage_path('app/private/pdf_receipts/receipt_' . $order->id . '.pdf');

        if (!file_exists($path)) {
            $order->load(['items.tshirtImage', 'items.color']);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.receipt', compact('order'))
                ->setPaper('a4', 'portrait');

            return $pdf->download('recibo-encomenda-' . $order->id . '.pdf');
        }

        return response()->download($path, 'recibo-encomenda-' . $order->id . '.pdf');
    }
}

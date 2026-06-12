<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderClosedNotification;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        if ($userType === 'C') {
            $orders = Order::where('customer_id', $user->id)
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        }

        if ($userType === 'F') {
            $orders = Order::whereIn('status', ['pending', 'processing'])->orderBy('id', 'desc')->paginate(10);
            return view('orders.index', compact('orders'));
        }

        if ($userType === 'A') {
            $query = Order::query();
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

        abort(403);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['items.tshirtImage', 'items.color']);
        return view('orders.show', compact('order'));
    }


    public function close(Order $order)
    {
        $this->authorize('close', $order);
        $orderStatus = strtolower(trim($order->status));
        $estadosParaFechar = ['pending', 'processing', 'paga', 'em_processamento', 'paid'];

        if (!in_array($orderStatus, $estadosParaFechar)) {
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Esta encomenda não pode ser fechada no estado atual (' . $order->status . ').');
        }

        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);
        $directory = storage_path('app/private/pdf_receipts');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdfPath = $directory . '/receipt_' . $order->id . '.pdf';
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', base_path());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.receipt', compact('order'))->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->setOptions($options);
        $pdf->save($pdfPath);

        $order->status = 'closed';
        $order->save();

        $customerUser = $order->customer->user;
        if ($customerUser) {
            Notification::send($customerUser, new OrderClosedNotification($order));
        }

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #' . $order->id . ' fechada com sucesso! Recibo gerado e arquivado.');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Esta encomenda não pode ser anulada.');
        }

        $request->validate([
            'reason_for_cancellation' => 'nullable|string|max:255',
        ]);

        $order->status = 'canceled';
        $order->reason_for_cancellation = $request->input('reason_for_cancellation');
        $order->save();

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #' . $order->id . ' anulada com sucesso.');
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
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.receipt', compact('order'))->setPaper('a4', 'portrait');
            return $pdf->download('recibo-encomenda-' . $order->id . '.pdf');
        }

        return response()->download($path, 'recibo-encomenda-' . $order->id . '.pdf');
    }
}
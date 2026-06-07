<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

use App\Notifications\OrderClosedNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // 1. CLIENTE ('C'): Apenas vê as suas próprias encomendas
        if ($userType === 'C') {
            $orders = Order::where('customer_id', $user->id)
                ->orderBy('date', 'desc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        }

        // 2. FUNCIONÁRIO ('F'): Vê encomendas pendentes ("pending") E em processamento ("processing")
        if ($userType === 'F') {
            $orders = Order::whereIn('status', ['pending', 'processing'])
                ->orderBy('date', 'asc')
                ->paginate(10);

            return view('orders.index', compact('orders'));
        }

        // 3. ADMINISTRADOR ('A'): Vê todas e pode filtrar por estado, cliente e data
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

            $orders = $query->orderBy('date', 'desc')->paginate(10);

            return view('orders.index', compact('orders'));
        }

        abort(403, 'Não tem permissão para aceder a esta página.');
    }


    public function show(Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));
        $order->load(['items.tshirtImage', 'items.color']);

        // Bloqueio de segurança para Clientes
        if ($userType === 'C' && $order->customer_id !== $user->id) {
            abort(403, 'Esta encomenda não lhe pertence.');
        }

        // CORREÇÃO ANTI-ERRO: Converte o estado da BD para minúsculas e limpa espaços
        $orderStatus = strtolower(trim($order->status));

        // Permitimos que o funcionário veja pendentes, em processamento e também as já fechadas/pagas
        $estadosPermitidos = ['pending', 'processing', 'closed', 'paga', 'em_processamento', 'paid'];

        if ($userType === 'F' && !in_array($orderStatus, $estadosPermitidos)) {
            abort(403, 'Os funcionários apenas podem aceder a encomendas operacionais. Estado atual da encomenda: ' . $order->status);
        }

        // Carrega os itens associados de forma imutável
        $order->load('items');

        return view('orders.show', compact('order'));
    }


    public function close(Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // ver se é func ou admin
        if ($userType !== 'F' && $userType !== 'A') {
            abort(403, 'Não tem permissão para fechar encomendas.');
        }

        $orderStatus = strtolower(trim($order->status));
        $estadosParaFechar = ['pending', 'processing', 'paga', 'em_processamento', 'paid'];

        if (!in_array($orderStatus, $estadosParaFechar)) {
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Esta encomenda não pode ser fechada no estado atual (' . $order->status . ').');
        }

        // Define o estado final. Se a tua BD exigir maiúsculas, muda para 'CLOSED'
        $order->status = 'closed'; 
        $order->save();

        // enviar o email de agradecimento
        $customerUser = $order->customer->user;
        if ($customerUser) {
            Notification::send($customerUser, new OrderClosedNotification($order));
        }

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #' . $order->id . ' fechada com sucesso!');
    }

    public function cancel(Request $request, Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // apenas o Administrador pode cancelar
        if ($userType !== 'A') {
            abort(403, 'Apenas administradores podem anular encomendas.');
        }

        // Pode ser cancelada se estiver pendente ou em processamento
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Esta encomenda não pode ser anulada.');
        }

        // Validação básica do texto de cancelamento se for enviado
        $request->validate([
            'reason_for_cancellation' => 'nullable|string|max:255',
        ]);

        $order->status = 'canceled';
        // Guarda no campo exato que tens no Fillable do teu modelo
        $order->reason_for_cancellation = $request->input('reason_for_cancellation');
        $order->save();

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #' . $order->id . ' anulada com sucesso.');
    }

    public function downloadReceipt(Order $order)
    {
        $user = Auth::user();
        $userType = $user ? strtoupper(trim($user->user_type)) : 'C';

        if ($userType === 'C' && $order->customer_id !== $user->id) {
            abort(403, 'Acesso negado.');
        }

        if ($order->status !== 'closed') {
            abort(404, 'O recibo só está disponível para encomendas fechadas.');
        }

        // Garante o carregamento das relações para evitar erros de propriedade nula na View
        $order->load(['items.tshirtImage', 'items.color']);

        // 1. Configurar as Opções do DomPDF usando a Classe correta (Evita o TypeError)
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', base_path()); // Permite o acesso seguro ao disco local

        // 2. Instanciar o PDF passando as opções configuradas
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.receipt', compact('order'))
            ->setPaper('a4', 'portrait');
            
        // Aplica as opções no motor interno
        $pdf->getDomPDF()->setOptions($options);

        return $pdf->download('recibo-encomenda-' . $order->id . '.pdf');
    }
}
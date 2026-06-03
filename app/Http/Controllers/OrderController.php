<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Lista as encomendas de acordo com o tipo de utilizador (Requisito G4)
     */
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

        // 2. FUNCIONÁRIO ('E'): Apenas consulta e acede a encomendas "pending"
        if ($userType === 'E') {
            $orders = Order::where('status', 'pending')
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

    /**
     * Mostra o detalhe completo de uma encomenda específica
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // Bloqueio de segurança para Clientes
        if ($userType === 'C' && $order->customer_id !== $user->id) {
            abort(403, 'Esta encomenda não lhe pertence.');
        }

        // Bloqueio de segurança para Funcionários (apenas acedem a pendentes)
        if ($userType === 'E' && $order->status !== 'pending') {
            abort(403, 'Os funcionários apenas podem aceder a encomendas pendentes.');
        }

        // O Administrador passa direto para qualquer uma

        // Carrega os itens associados de forma imutável
        $order->load('items');

        return view('orders.show', compact('order'));
    }


    public function close(Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // Validar se é funcionário (E) ou administrador (A)
        if ($userType !== 'E' && $userType !== 'A') {
            abort(403, 'Não tem permissão para fechar encomendas.');
        }

        // Apenas encomendas pendentes podem ser fechadas
        if ($order->status !== 'pending') {
            return back()->with('alert-type', 'warning')->with('alert-msg', 'Esta encomenda não está pendente.');
        }

        $order->status = 'closed';
        $order->save();

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #'.$order->id.' fechada com sucesso!');
    }

    /**
     * ADMINISTRAÇÃO: Declara a encomenda como "anulada" (canceled) com razão opcional
     */
    public function cancel(Request $request, Order $order)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        // Apenas o Administrador pode cancelar
        if ($userType !== 'A') {
            abort(403, 'Apenas administradores podem anular encomendas.');
        }

        if ($order->status !== 'pending') {
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

        return back()->with('alert-type', 'success')->with('alert-msg', 'Encomenda #'.$order->id.' anulada com sucesso.');
    }

    public function downloadReceipt(Order $order)
    {
        $user = Auth::user();
        $userType = $user ? strtoupper(trim($user->user_type)) : 'A'; 
        $currentUserId = Auth::id() ?? 22; 

        // Segurança básica: Clientes só descarregam os seus próprios recibos
        if ($userType === 'C' && $order->customer_id !== $currentUserId) {
            abort(403, 'Acesso negado.');
        }

        if ($order->status !== 'closed') {
            abort(404, 'O recibo só está disponível para encomendas fechadas.');
        }

        // Caminho baseado na pasta privada (Slide 12 e 13)
        $path = storage_path('app/private/pdf_receipts/receipt_' . $order->id . '.pdf'); 

        if (!file_exists($path)) {
            abort(404, 'O ficheiro do recibo não foi encontrado no servidor.');
        }

        return response()->download($path, 'recibo-encomenda-' . $order->id . '.pdf'); 
    }
}
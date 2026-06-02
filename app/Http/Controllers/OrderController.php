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
        $order->load('items.tshirtImage');

        return view('orders.show', compact('order'));
    }
}
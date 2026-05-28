<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // <-- CRUCIAL: Para comunicar com a API externa
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;

class CheckoutController extends Controller
{
    /**
     * Mostra o formulário de Checkout.
     */
    public function show()
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        if ($userType !== 'C') {
            return redirect()->route('home')->with('alert-type', 'warning')->with('alert-msg', 'Apenas clientes podem efetuar o checkout.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('alert-type', 'warning')->with('alert-msg', 'O seu carrinho está vazio.');
        }

        $customer = Customer::find($user->id);
        $totalPrice = array_sum(array_column($cart, 'sub_total'));

        return view('checkout.show', compact('cart', 'customer', 'totalPrice'));
    }

    /**
     * PROCESSA O PAGAMENTO SIMULADO E REGISTA A ENCOMENDA
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $userType = strtoupper(trim($user->user_type));

        if ($userType !== 'C') {
            return redirect()->route('home');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('alert-type', 'warning')->with('alert-msg', 'O carrinho está vazio.');
        }

        // 1. VALIDAÇÃO ESTRITA (Regras exatas da FunShirt e da API de pagamentos)
        $request->validate([
            'nif' => 'required|digits:9',
            'address' => 'required|string|max:255',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'notes' => 'nullable|string|max:1000',
            'payment_ref' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->input('payment_type');
                    if ($type === 'Visa' && !preg_match('/^4[0-9]{15}$/', $value)) {
                        $fail('O formato para Visa deve ter 16 dígitos e começar por 4.');
                    }
                    if ($type === 'PayPal' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail('O formato para PayPal deve ser um e-mail válido.');
                    }
                    if ($type === 'MB WAY' && !preg_match('/^9[0-9]{8}$/', $value)) {
                        $fail('O formato para MB WAY deve ter 9 dígitos e começar por 9.');
                    }
                },
            ],
        ]);

        // Calcular e fixar o valor total com o formato de 2 casas decimais exigido pela API
        $totalPrice = round(array_sum(array_column($cart, 'sub_total')), 2);

        // 2. PROCESSAMENTO DO PAGAMENTO SIMULADO EXTERNO
        try {
            // Envia o payload JSON exatamente como a API espera
            $response = Http::post('https://ainet-payments-api.vercel.app/api/payments', [
                'type' => $request->input('payment_type'),
                'reference' => $request->input('payment_ref'),
                'value' => $totalPrice,
            ]);

            // O enunciado diz: "apenas em caso de sucesso (status 201 Created) regista a encomenda"
            if ($response->status() !== 201) {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Transação recusada pela plataforma simulada.';
                
                return back()->withInput()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', 'Pagamento Recusado: ' . $errorMessage);
            }

        } catch (\Exception $e) {
            // Caso o endpoint da Vercel esteja offline ou dê erro de rede
            return back()->withInput()
                ->with('alert-type', 'danger')
                ->with('alert-msg', 'Erro de comunicação com o sistema de pagamentos. Tente novamente.');
        }

        // 3. PERSISTÊNCIA INTEGRAL E IMUTÁVEL NA BASE DE DADOS
        // Usamos DB::beginTransaction para garantir que se um item falhar, nada é gravado por engano
        DB::beginTransaction();
        try {
            // Criar a encomenda principal no estado "pending"
            $order = new Order();
            $order->status = 'pending'; // Conforme exigido: "pendente" (pending)
            $order->customer_id = $user->id;
            $order->date = now();
            $order->total_price = $totalPrice;
            $order->nif = $request->input('nif');
            $order->address = $request->input('address');
            $order->payment_type = $request->input('payment_type');
            $order->payment_ref = $request->input('payment_ref');
            $order->notes = $request->input('notes');
            $order->save();

            // Replicar integralmente a informação do carrinho para garantir imutabilidade histórica
            foreach ($cart as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->tshirt_image_id = $item['tshirt_image_id'];
                $orderItem->color_code = $item['color_code'];
                $orderItem->size = $item['size'];
                $orderItem->qty = $item['qty'];
                
                // CONGELAMENTO DE VALORES: Salva o preço unitário e o subtotal gerados na sessão. 
                // Se amanhã o administrador mudar o preço base na tabela 'prices', esta encomenda não se altera.
                $orderItem->unit_price = $item['unit_price'];
                $orderItem->sub_total = $item['sub_total'];
                $orderItem->save();
            }

            DB::commit();

            // 4. LIMPAR O CARRINHO (Apenas após o sucesso absoluto na BD)
            session()->forget('cart');

            return redirect()->route('home')
                ->with('alert-type', 'success')
                ->with('alert-msg', 'Encomenda registada com sucesso! O pagamento foi validado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('alert-type', 'danger')
                ->with('alert-msg', 'Erro crítico ao guardar os dados da encomenda: ' . $e->getMessage());
        }
    }
}
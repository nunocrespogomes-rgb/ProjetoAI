<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;

use App\Notifications\OrderCreatedPending;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userType = $user ? strtoupper(trim($user->user_type)) : null;

        if ($userType !== 'C') {
            return redirect()->route('home')->with('alert-type', 'warning')->with('alert-msg', 'Apenas clientes podem efetuar o checkout.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('alert-type', 'warning')->with('alert-msg', 'O seu carrinho está vazio.');
        }

        $customer = Customer::find($user->id);

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['qty'] * $item['unit_price'];
        }

        return view('checkout.show', compact('cart', 'customer', 'totalPrice'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userType = $user ? strtoupper(trim($user->user_type)) : null;

        //validações de permissões (cliente)
        if ($userType !== 'C') {
            return redirect()->route('home');
        }

        //tratamento do carrinho
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('alert-type', 'warning')->with('alert-msg', 'O carrinho está vazio.');
        }

        //validações de formatos
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

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['qty'] * $item['unit_price'];
        }
        $totalPrice = round($totalPrice, 2);

        try {

            //pagamento em si
            $response = Http::post('https://ainet-payments-api.vercel.app/api/payments', [
                'type' => $request->input('payment_type'),
                'reference' => $request->input('payment_ref'),
                'value' => $totalPrice,
            ]);

            //verificação do Status Code
            if ($response->status() !== 201) {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Transação recusada pela plataforma simulada.';

                return back()->withInput()
                    ->with('alert-type', 'error')
                    ->with('alert-msg', 'Pagamento Recusado: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('alert-type', 'error')
                ->with('alert-msg', 'Erro de comunicação com o sistema de pagamentos. Tente novamente.');
        }

        //só começa o registo se o código for 201
        //tudo o resto é tratado (erro de conexão ou cod != 201)

        DB::beginTransaction();
        try {
            $order = new Order();
            $order->status = 'pending';
            $order->customer_id = $user->id;
            $order->date = now()->format('Y-m-d');
            $order->total_price = $totalPrice;
            $order->nif = $request->input('nif');
            $order->address = $request->input('address');
            $order->payment_type = $request->input('payment_type');
            $order->payment_ref = $request->input('payment_ref');
            $order->notes = $request->input('notes');
            $order->receipt_url = 'receipt_' . $order->id . '.pdf';
            $order->save();

            foreach ($cart as $item) {
                $orderItem = new OrderItem();

                //o save(), guarda a data nos respetivos campos,
                //que só existem na order e não no orderItem
                //esta linha impede a procura pelas colunas, que não existem e dá erro
                $orderItem->timestamps = false;

                $orderItem->order_id = $order->id;
                $orderItem->tshirt_image_id = $item['tshirt_image_id'];
                $orderItem->color_code = $item['color_code'];
                $orderItem->size = $item['size'];
                $orderItem->qty = $item['qty'];
                $orderItem->unit_price = $item['unit_price'];
                $orderItem->sub_total = $item['sub_total'];

                $orderItem->save(); // O Laravel já não vai tentar injetar o created_at/updated_at
            }

            DB::commit();

            Notification::send($user, new OrderCreatedPending($order));

            session()->forget('cart');

            return redirect()->route('orders.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', 'Encomenda registada com sucesso! O pagamento foi validado.');
        } catch (\Exception $e) {
            //caso haja algum erro a meio, cancela a operação
            DB::rollBack();
            return back()->withInput()
                ->with('alert-type', 'error')
                ->with('alert-msg', 'Erro crítico ao guardar os dados da encomenda: ' . $e->getMessage());
        }
    }
}

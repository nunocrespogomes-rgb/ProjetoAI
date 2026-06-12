<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;

use App\Http\Requests\StoreCheckoutRequest;
use App\Notifications\OrderCreatedPending;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function show()
    {
        if (!Auth::user()->isCustomer()) {
            return redirect()->route('home')
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'Apenas clientes podem efetuar o checkout.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'O seu carrinho está vazio.');
        }

        $customer = Customer::find(Auth::id());
        $totalPrice = collect($cart)->sum(fn($item) => $item['qty'] * $item['unit_price']);

        return view('checkout.show', compact('cart', 'customer', 'totalPrice'));
    }

    public function store(StoreCheckoutRequest $request)
    {
        if (!Auth::user()->isCustomer()) {
            return redirect()->route('home');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'O carrinho está vazio.');
        }

        $validated = $request->validated();

        $totalPrice = round(
            collect($cart)->sum(fn($item) => $item['qty'] * $item['unit_price']),
            2
        );

        try {
            $response = Http::withoutVerifying()->post('https://ainet-payments-api.vercel.app/api/payments', [
                'type'      => $validated['payment_type'],
                'reference' => $validated['payment_ref'],
                'value'     => $totalPrice,
            ]);

            if ($response->status() !== 201) {
                $errorMessage = $response->json('message') ?? 'Transação recusada pela plataforma simulada.';

                return back()->withInput()
                    ->with('alert-type', 'error')
                    ->with('alert-msg', 'Pagamento Recusado: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('alert-type', 'error')
                ->with('alert-msg', 'Erro de comunicação com o sistema de pagamentos. Tente novamente.');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'status'       => 'pending',
                'customer_id'  => Auth::id(),
                'date'         => now()->toDateString(),
                'total_price'  => $totalPrice,
                'nif'          => $validated['nif'],
                'address'      => $validated['address'],
                'payment_type' => $validated['payment_type'],
                'payment_ref'  => $validated['payment_ref'],
                'notes'        => $validated['notes'] ?? null,
            ]);

            $order->update(['receipt_url' => 'receipt_' . $order->id . '.pdf']);

            foreach ($cart as $item) {
                OrderItem::insert([
                    'order_id'        => $order->id,
                    'tshirt_image_id' => $item['tshirt_image_id'],
                    'color_code'      => $item['color_code'],
                    'size'            => $item['size'],
                    'qty'             => $item['qty'],
                    'unit_price'      => $item['unit_price'],
                    'sub_total'       => $item['sub_total'],
                ]);
            }

            DB::commit();

            Notification::send(Auth::user(), new OrderCreatedPending($order));
            session()->forget('cart');

            return redirect()->route('orders.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', 'Encomenda registada com sucesso! O pagamento foi validado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('alert-type', 'error')
                ->with('alert-msg', 'Erro crítico ao guardar os dados da encomenda: ' . $e->getMessage());
        }
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Requests\RemoveCartItemRequest;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AddCartItemFormRequest;
use App\Http\Requests\UpdateCartItemFormRequest;

class CartController extends Controller
{
    public function index(): View
    {
//        $cart = session('cart', []);
//        return view('cart.show', compact('cart'));

        $cart = session('cart', []);
        $colors = Color::orderBy('name')->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        return view('cart.index', compact('cart', 'colors', 'sizes'));

    }

    public function addToCart(AddCartItemFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $cart = session('cart', []);

        $tshirtImage = TshirtImage::findOrFail($validated['tshirt_image_id']);
        $color = Color::where('code', $validated['color_code'])->firstOrFail();

        $unitPrice = $this->calculateUnitPrice(
            $tshirtImage,
            $validated['qty']
        );

        $key = $validated['tshirt_image_id'] . '_' . $validated['color_code'] . '_' . $validated['size'];

        if (array_key_exists($key, $cart)) {
            $cart[$key]['qty'] += $validated['qty'];

            $cart[$key]['unit_price'] = $this->calculateUnitPrice(
                $tshirtImage,
                $cart[$key]['qty']
            );

            $cart[$key]['sub_total'] = $cart[$key]['qty'] * $cart[$key]['unit_price'];

            $msg = 'Quantidade atualizada no carrinho!';
        } else {
            $cart[$key] = [
                'tshirt_image_id' => $tshirtImage->id,
                'customer_id' => $tshirtImage->customer_id,
                'name' => $tshirtImage->name,
                'image_url' => $tshirtImage->image_url,
                'color_code' => $color->code,
                'color_name' => $color->name,
                'size' => $validated['size'],
                'qty' => $validated['qty'],
                'unit_price' => $unitPrice,
                'sub_total' => $validated['qty'] * $unitPrice,
                'is_custom' => $tshirtImage->customer_id !== null,
            ];

            $msg = 'T-shirt adicionada ao carrinho!';
        }

        session(['cart' => $cart]);

        return back()
            ->with('alert-msg', $msg)
            ->with('alert-type', 'success');
    }

    public function removeFromCart(RemoveCartItemRequest $request, string $itemKey): RedirectResponse
    {
        $cart = session('cart', []);

        if (array_key_exists($itemKey, $cart)) {
            unset($cart[$itemKey]);

            if (empty($cart)) {
                $request->session()->forget('cart');
            } else {
                session(['cart' => $cart]);
            }

            return back()
                ->with('alert-msg', 'Item removido com sucesso!')
                ->with('alert-type', 'success');
        }

        return back()
            ->with('alert-msg', 'Erro ao remover item.')
            ->with('alert-type', 'warning');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'O carrinho foi limpo.');
    }

    public function updateCart(UpdateCartItemFormRequest $request, string $itemKey): RedirectResponse
    {
        $validated = $request->validated();


        $cart = session('cart', []);

        if (!array_key_exists($itemKey, $cart)) {
            return back()
                ->with('alert-msg', 'Item não encontrado!')
                ->with('alert-type', 'warning');
        }

        if ($validated['qty'] <= 0) {
            unset($cart[$itemKey]);

            if (empty($cart)) {
                $request->session()->forget('cart');
            } else {
                session(['cart' => $cart]);
            }

            return back()
                ->with('alert-msg', 'Item removido do carrinho.')
                ->with('alert-type', 'success');
        }

        $oldItem = $cart[$itemKey];

        $tshirtImage = TshirtImage::findOrFail($oldItem['tshirt_image_id']);
        $color = Color::where('code', $validated['color_code'])->firstOrFail();

        $newUnitPrice = $this->calculateUnitPrice($tshirtImage, $validated['qty']);

        $newKey = $oldItem['tshirt_image_id'] . '_' . $validated['color_code'] . '_' . $validated['size'];

        $updatedItem = [
            'tshirt_image_id' => $oldItem['tshirt_image_id'],
            'customer_id' => $tshirtImage->customer_id,
            'name' => $oldItem['name'],
            'image_url' => $oldItem['image_url'],
            'color_code' => $color->code,
            'color_name' => $color->name,
            'size' => $validated['size'],
            'qty' => $validated['qty'],
            'unit_price' => $newUnitPrice,
            'sub_total' => $validated['qty'] * $newUnitPrice,
            'is_custom' => $oldItem['is_custom'] ?? false,
        ];

        unset($cart[$itemKey]);

        if (array_key_exists($newKey, $cart)) {
            $cart[$newKey]['qty'] += $updatedItem['qty'];

            $cart[$newKey]['unit_price'] = $this->calculateUnitPrice(
                $tshirtImage,
                $cart[$newKey]['qty']
            );

            $cart[$newKey]['sub_total'] = $cart[$newKey]['qty'] * $cart[$newKey]['unit_price'];
        } else {
            $cart[$newKey] = $updatedItem;
        }

        session(['cart' => $cart]);

        return back()
            ->with('alert-msg', 'Carrinho atualizado com sucesso!')
            ->with('alert-type', 'success');
    }

    private function calculateUnitPrice(TshirtImage $tshirtImage, int $qty): float
    {
        $price = Price::first();

        if (!$price) {
            return 0;
        }

        $isCustomImage = $tshirtImage->customer_id !== null;
        $hasDiscount = $qty >= $price->qty_discount;

        if ($isCustomImage) {
            return (float)($hasDiscount
                ? $price->unit_price_own_discount
                : $price->unit_price_own);
        }

        return (float)($hasDiscount
            ? $price->unit_price_catalog_discount
            : $price->unit_price_catalog);
    }
}

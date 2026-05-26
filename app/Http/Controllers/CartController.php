<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\View\View;
//use Illuminate\Http\RedirectResponse;
//use App\Models\Discipline;
//use App\Models\Student;
//use App\Http\Requests\CartConfirmationFormRequest;
//use Illuminate\Support\Facades\DB;
//
//class CartController extends Controller
//{
//    public function show(): View
//    {
//        $cart = session('cart', []);
//        return view('cart.show', compact('cart'));
//    }
//
//    public function addToCart(Request $request, Discipline $discipline): RedirectResponse
//    {
//
//        // 1. Validar os dados vindos do formulário (botão adicionar da vista do catálogo)
//        $validated = $request->validate([
//            'tshirt_image_id' => 'required|integer',
//            'color_code' => 'required|string',
//            'size' => 'required|in:XS,S,M,L,XL',
//            'qty' => 'required|integer|min:1' //quantidade
//        ]);
//
//        $cart = session('cart', []);
//
//        // 2. A key tem de ser composta por id, cor e tamanho,
//        //para haverem as mesmas t-shirts com tamanhos diferentes
//
//        $key = $validated['tshirt_image_id'] . '_' . $validated['color_code'] . '_' . $validated['size'];
//
//        // 3. Se o item já existe, somamos a quantidade. Se não, adicionamos de novo.
//        if (array_key_exists($key, $cart)) {
//            $cart[$key]['qty'] += $validated['qty'];
//            $msg = "Quantidade atualizada no carrinho!";
//        } else {
//            // Aqui deves ir buscar o Preço à BD (Tabela Prices) e o Nome (Tabela TshirtImages)
//            // Por agora, vou meter dados fictícios para a lógica funcionar
//            $cart[$key] = [
//                'tshirt_image_id' => $validated['tshirt_image_id'],
//                'color_code' => $validated['color_code'],
//                'size' => $validated['size'],
//                'qty' => $validated['qty'],
//                'name' => 'Nome Temporário',
//                'price' => 10.00
//            ];
//            $msg = "T-shirt adicionada ao carrinho!";
//        }
//
//        session(['cart' => $cart]);
//
//        return back()->with('alert-msg', $msg)->with('alert-type', 'success');
//    }
//
//
//    public function removeFromCart(Request $request, $item_key): RedirectResponse
//    {
//        $cart = session('cart', []);
//
//        if (array_key_exists($item_key, $cart)) {
//            unset($cart[$item_key]);
//            session(['cart' => $cart]);
//            return back()->with('alert-msg', "Item removido com sucesso!")->with('alert-type', 'success');
//        }
//
//        return back()->with('alert-msg', "Erro ao remover item.")->with('alert-type', 'warning');
//    }
//
//
//    public function destroy(Request $request): RedirectResponse
//    {
//        $request->session()->forget('cart');
//        return back()
//            ->with('alert-type', 'Sucesso.')
//            ->with('alert-msg', 'O Carrinho foi limpo.');
//    }
//
//    // O antigo confirm (registo de disciplinas) tem de ser totalmente refeito
//    // para a lógica de Checkout (NIF, Morada, Pagamento Simulados).
//    // Por agora, podes deixá-lo vazio até chegares ao Grupo G4.
//    public function confirm(Request $request)
//    {
//        // Lógica de validar NIF, Morada e chamar a API de Pagamentos.
//    }
//
//
//
//    public function updateCart(Request $request, $item_key): RedirectResponse
//    {
//        $cart = session('cart', []);
//
//        if (!array_key_exists($item_key, $cart)) {
//            return back()->with('alert-msg', "Item não encontrado!")->with('alert-type', 'warning');
//        }
//
//        // Vai buscar os novos valores do formulário (se não forem enviados, mantém os atuais)
//        $newQty = $request->input('qty', $cart[$item_key]['qty']);
//        $newColor = $request->input('color_code', $cart[$item_key]['color_code']);
//        $newSize = $request->input('size', $cart[$item_key]['size']);
//
//        // Regra do Zero: remover se a quantidade for <= 0
//        if ($newQty <= 0) {
//            unset($cart[$item_key]);
//            session(['cart' => $cart]);
//            return back()->with('alert-msg', "Item removido do carrinho.")->with('alert-type', 'success');
//        }
//
//        // Criar a nova chave caso a cor ou tamanho tenham mudado
//        $newKey = $cart[$item_key]['tshirt_image_id'] . '_' . $newColor . '_' . $newSize;
//
//        // Se a chave mudou (o utilizador alterou a cor ou tamanho)
//        if ($newKey !== $item_key) {
//            // Verifica se a nova combinação já existe no carrinho (ex: mudou para Branco L, mas já tinha Branco L no carrinho)
//            if (array_key_exists($newKey, $cart)) {
//                $cart[$newKey]['qty'] += $newQty; // Soma as quantidades
//            } else {
//                // Cria o novo item com as novas características
//                $cart[$newKey] = $cart[$item_key];
//                $cart[$newKey]['qty'] = $newQty;
//                $cart[$newKey]['color_code'] = $newColor;
//                $cart[$newKey]['size'] = $newSize;
//            }
//            unset($cart[$item_key]); // Remove o item antigo
//            $msg = "Cor/Tamanho atualizados com sucesso!";
//        } else {
//            // Se a chave for igual, significa que só alterou a quantidade
//            $cart[$item_key]['qty'] = $newQty;
//            $msg = "Quantidade atualizada com sucesso!";
//        }
//
//        session(['cart' => $cart]);
//        return back()->with('alert-msg', $msg)->with('alert-type', 'success');
//    }
//
//}


namespace App\Http\Controllers;

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
    public function show(): View
    {
//        $cart = session('cart', []);
//        return view('cart.show', compact('cart'));

        $cart = session('cart', []);
        $colors = Color::orderBy('name')->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        return view('cart.show', compact('cart', 'colors', 'sizes'));

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

    public function removeFromCart(Request $request, string $itemKey): RedirectResponse
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

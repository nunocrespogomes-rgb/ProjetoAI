<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Discipline;
use App\Models\Student;
use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Discipline $discipline): RedirectResponse
    {

        // 1. Validar os dados vindos do formulário (botão adicionar da vista do catálogo)
        $validated = $request->validate([
            'tshirt_image_id' => 'required|integer',
            'color_code' => 'required|string',
            'size' => 'required|in:XS,S,M,L,XL',
            'qty' => 'required|integer|min:1' //quantidade
        ]);

        $cart = session('cart', []);

        // 2. A key tem de ser composta por id, cor e tamanho, 
        //para haverem as mesmas t-shirts com tamanhos diferentes

        $key = $validated['tshirt_image_id'] . '_' . $validated['color_code'] . '_' . $validated['size'];

        // 3. Se o item já existe, somamos a quantidade. Se não, adicionamos de novo.
        if (array_key_exists($key, $cart)) {
            $cart[$key]['qty'] += $validated['qty'];
            $msg = "Quantidade atualizada no carrinho!";
        } else {
            // Aqui deves ir buscar o Preço à BD (Tabela Prices) e o Nome (Tabela TshirtImages)
            // Por agora, vou meter dados fictícios para a lógica funcionar
            $cart[$key] = [
                'tshirt_image_id' => $validated['tshirt_image_id'],
                'color_code' => $validated['color_code'],
                'size' => $validated['size'],
                'qty' => $validated['qty'],
                'name' => 'Nome Temporário',
                'price' => 10.00
            ];
            $msg = "T-shirt adicionada ao carrinho!";
        }

        session(['cart' => $cart]);

        return back()->with('alert-msg', $msg)->with('alert-type', 'success');
    }


    public function removeFromCart(Request $request, $item_key): RedirectResponse
    {
        $cart = session('cart', []);

        if (array_key_exists($item_key, $cart)) {
            unset($cart[$item_key]);
            session(['cart' => $cart]);
            return back()->with('alert-msg', "Item removido com sucesso!")->with('alert-type', 'success');
        }

        return back()->with('alert-msg', "Erro ao remover item.")->with('alert-type', 'warning');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'Sucesso.')
            ->with('alert-msg', 'O Carrinho foi limpo.');
    }

    // O antigo confirm (registo de disciplinas) tem de ser totalmente refeito
    // para a lógica de Checkout (NIF, Morada, Pagamento Simulados).
    // Por agora, podes deixá-lo vazio até chegares ao Grupo G4.
    public function confirm(Request $request)
    {
        // Lógica de validar NIF, Morada e chamar a API de Pagamentos.
    }

    

    public function updateCart(Request $request, $item_key): RedirectResponse
    {
        $cart = session('cart', []);

        if (!array_key_exists($item_key, $cart)) {
            return back()->with('alert-msg', "Item não encontrado!")->with('alert-type', 'warning');
        }

        // Vai buscar os novos valores do formulário (se não forem enviados, mantém os atuais)
        $newQty = $request->input('qty', $cart[$item_key]['qty']);
        $newColor = $request->input('color_code', $cart[$item_key]['color_code']);
        $newSize = $request->input('size', $cart[$item_key]['size']);

        // Regra do Zero: remover se a quantidade for <= 0
        if ($newQty <= 0) {
            unset($cart[$item_key]);
            session(['cart' => $cart]);
            return back()->with('alert-msg', "Item removido do carrinho.")->with('alert-type', 'success');
        }

        // Criar a nova chave caso a cor ou tamanho tenham mudado
        $newKey = $cart[$item_key]['tshirt_image_id'] . '_' . $newColor . '_' . $newSize;

        // Se a chave mudou (o utilizador alterou a cor ou tamanho)
        if ($newKey !== $item_key) {
            // Verifica se a nova combinação já existe no carrinho (ex: mudou para Branco L, mas já tinha Branco L no carrinho)
            if (array_key_exists($newKey, $cart)) {
                $cart[$newKey]['qty'] += $newQty; // Soma as quantidades
            } else {
                // Cria o novo item com as novas características
                $cart[$newKey] = $cart[$item_key];
                $cart[$newKey]['qty'] = $newQty;
                $cart[$newKey]['color_code'] = $newColor;
                $cart[$newKey]['size'] = $newSize;
            }
            unset($cart[$item_key]); // Remove o item antigo
            $msg = "Cor/Tamanho atualizados com sucesso!";
        } else {
            // Se a chave for igual, significa que só alterou a quantidade
            $cart[$item_key]['qty'] = $newQty;
            $msg = "Quantidade atualizada com sucesso!";
        }

        session(['cart' => $cart]);
        return back()->with('alert-msg', $msg)->with('alert-type', 'success');
    }
    
}

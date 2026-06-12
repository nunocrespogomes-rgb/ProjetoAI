<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminPriceController extends Controller
{
    public function edit(): View
    {
        $price = Price::first() ?? new Price([
            'unit_price_catalog'          => 0,
            'unit_price_own'              => 0,
            'unit_price_catalog_discount' => 0,
            'unit_price_own_discount'     => 0,
            'qty_discount'                => 1,
        ]);

        $this->authorize('view', $price);

        return view('admin.prices.edit', compact('price'));
    }

    public function update(UpdatePriceRequest $request): RedirectResponse
    {
        // authorize() tratado no UpdatePriceRequest
        $validated = $request->validated();

        $price = Price::first() ?? new Price();
        $price->unit_price_catalog          = $validated['unit_price_catalog'];
        $price->unit_price_own              = $validated['unit_price_own'];
        $price->unit_price_catalog_discount = $validated['unit_price_catalog_discount'];
        $price->unit_price_own_discount     = $validated['unit_price_own_discount'];
        $price->qty_discount                = $validated['qty_discount'];
        $price->save();

        return redirect()
            ->route('admin.prices.edit')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Preços atualizados com sucesso.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminPriceController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && strtoupper(trim(Auth::user()->user_type)) === 'A', 403);
    }

    public function edit(): View
    {
        $this->authorizeAdmin();

        $price = Price::query()->first() ?? new Price([
            'unit_price_catalog' => 0,
            'unit_price_own' => 0,
            'unit_price_catalog_discount' => 0,
            'unit_price_own_discount' => 0,
            'qty_discount' => 1,
        ]);

        return view('admin.prices.edit', compact('price'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'unit_price_catalog' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_own' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_catalog_discount' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'unit_price_own_discount' => ['required', 'numeric', 'min:0.01', 'max:999999.99', 'decimal:0,2'],
            'qty_discount' => ['required', 'integer', 'min:1', 'max:999999'],
        ]);

        $price = Price::query()->first() ?? new Price();
        $price->unit_price_catalog = $validated['unit_price_catalog'];
        $price->unit_price_own = $validated['unit_price_own'];
        $price->unit_price_catalog_discount = $validated['unit_price_catalog_discount'];
        $price->unit_price_own_discount = $validated['unit_price_own_discount'];
        $price->qty_discount = $validated['qty_discount'];
        $price->save();

        return redirect()
            ->route('admin.prices.edit')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Preços atualizados com sucesso.');
    }
}

@php
    $total = collect($cart)->sum(fn ($item) => $item['sub_total'] ?? ($item['unit_price'] * $item['qty']));
@endphp

<div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sticky top-6">

    <h2 class="text-xl font-black text-zinc-900 dark:text-white mb-4">
        Resumo
    </h2>

    <div class="flex justify-between text-sm mb-3">
        <span class="text-zinc-500 dark:text-zinc-400">
            Subtotal
        </span>

        <span class="font-semibold text-zinc-900 dark:text-white">
            {{ number_format($total, 2) }} €
        </span>
    </div>

    <div class="flex justify-between text-sm mb-5">
        <span class="text-zinc-500 dark:text-zinc-400">
            Envio
        </span>

        <span class="font-semibold text-emerald-600 dark:text-emerald-400">
            Grátis
        </span>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between items-center">
        <span class="font-bold text-zinc-900 dark:text-white">
            Total
        </span>

        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
            {{ number_format($total, 2) }} €
        </span>
    </div>

    <div class="mt-6">
        <flux:button href="{{ route('cart.checkout') }}"
                     variant="primary"
                     icon="credit-card"
                     class="w-full justify-center">
            Finalizar Compra
        </flux:button>
    </div>

    <form action="{{ route('cart.destroy') }}"
          method="POST"
          class="mt-3">
        @csrf
        @method('DELETE')

        <flux:button type="submit"
                     variant="ghost"
                     class="w-full justify-center">
            Limpar Carrinho
        </flux:button>
    </form>

</div>

<x-layouts::main-content
    title="Carrinho"
    heading="Carrinho"
    subheading="Revê os produtos antes de finalizar">

    <div class="p-6">

        @if(empty($cart))

            <div
                class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 text-center">
                <p class="text-zinc-500 dark:text-zinc-400">
                    O carrinho está vazio.
                </p>

                <div class="mt-4">
                    <flux:button href="{{ route('catalog.index') }}" variant="primary">
                        Voltar ao Catálogo
                    </flux:button>
                </div>
            </div>

        @else

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">

                    @foreach($cart as $cartKey => $item)

                        <div
                            class="flex gap-4 w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">

                            <div
                                class="h-24 w-24 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                                @if($item['image_url'])
                                    <img src="{{ asset('storage/tshirt_images/' . $item['image_url']) }}"
                                         class="h-full w-full object-contain"
                                         alt="{{ $item['name'] }}">
                                @else
                                    <span class="text-xs text-zinc-400">Sem imagem</span>
                                @endif
                            </div>

                            <div class="flex-1">

                                <h3 class="font-bold text-zinc-900 dark:text-white">
                                    {{ $item['name'] }}
                                </h3>

                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                    Tamanho: {{ $item['size'] }}
                                </p>

                                <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mt-1 whitespace-nowrap">
                                    Cor: {{ $item['color_name'] }}
                                </div>

                                <p class="whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                    Quantidade: {{ $item['qty'] }}
                                </p>

                                {{-- EDITAR ITEM --}}
                                <details class="mt-3">
                                    <summary
                                        class="inline-flex cursor-pointer items-center rounded-md bg-zinc-100 px-3 py-1.5 text-xs font-semibold text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700">
                                        Editar
                                    </summary>

                                    <form action="{{ route('cart.update', $cartKey) }}"
                                          method="POST"
                                          class="mt-3 space-y-3">

                                        @csrf
                                        @method('PUT')

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                                            <div>
                                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1">
                                                    Tamanho
                                                </label>

                                                <select name="size"
                                                        required
                                                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                                                    @foreach($sizes as $size)
                                                        <option value="{{ $size }}"
                                                            {{ $item['size'] === $size ? 'selected' : '' }}>
                                                            {{ $size }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold text-zinc-500 dark:text-zinc-400 mb-1">
                                                    Cor
                                                </label>

                                                <select name="color_code"
                                                        required
                                                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm">
                                                    @foreach($colors as $color)
                                                        <option value="{{ $color->code }}"
                                                            {{ $item['color_code'] === $color->code ? 'selected' : '' }}>
                                                            {{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <livewire:counterCartEdit
                                                :qty="$item['qty']"
                                                input-name="qty"
                                                :key="'counter-'.$cartKey"
                                            />

                                        </div>

                                        <div class="flex items-center gap-3">
                                            <flux:button type="submit"
                                                         variant="primary"
                                                         size="sm">
                                                Guardar alterações
                                            </flux:button>


                                        </div>

                                    </form>
                                </details>

                            </div>

                            <div class="text-right">

                                <p class="font-bold text-zinc-900 dark:text-white">
                                    {{ number_format($item['sub_total'] ?? ($item['unit_price'] * $item['qty']), 2) }} €
                                </p>

                                <form action="{{ route('cart.remove', $cartKey) }}"
                                      method="POST"
                                      class="mt-4">

                                    @csrf
                                    @method('DELETE')

                                    <flux:button type="submit" variant="danger" size="sm">
                                        Remover
                                    </flux:button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="lg:col-span-1">

                    @php
                        $total = collect($cart)->sum(fn ($item) => $item['sub_total'] ?? ($item['unit_price'] * $item['qty']));
                    @endphp

                    <div
                        class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-6 sticky top-6">

                        <h2 class="text-xl font-black text-zinc-900 dark:text-white mb-4">
                            Resumo
                        </h2>

                        <div class="flex justify-between text-sm mb-3">
                            <span class="text-zinc-500 dark:text-zinc-400">Subtotal</span>
                            <span class="font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($total, 2) }} €
                            </span>
                        </div>

                        <div class="flex justify-between text-sm mb-5">
                            <span class="text-zinc-500 dark:text-zinc-400">Envio</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                                Grátis
                            </span>
                        </div>

                        <div
                            class="border-t border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between items-center">
                            <span class="font-bold text-zinc-900 dark:text-white">Total</span>
                            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                                {{ number_format($total, 2) }} €
                            </span>
                        </div>

                        <div class="mt-6">
                            <flux:button href="#"
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

                </div>

            </div>

        @endif

    </div>

</x-layouts::main-content>

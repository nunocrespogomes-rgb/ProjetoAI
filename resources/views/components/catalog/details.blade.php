<div class="flex flex-col justify-between">
    <div>
        <div class="mb-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 mb-2">
                {{ $tshirtImage->category->name ?? 'Geral' }}
            </span>

            <h1 class="text-2xl font-extrabold text-zinc-900 dark:text-white">
                {{ $tshirtImage->name }}
            </h1>

            <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                {{ $tshirtImage->description ?? 'Esta estampa exclusiva está disponível para aplicação em qualquer um dos tamanhos e cores selecionados abaixo.' }}
            </p>
        </div>

        <hr class="border-zinc-200 dark:border-zinc-700 my-4" />

        <form action="{{ route('cart.add') }}" method="POST" class="space-y-5">
            @csrf

            <input type="hidden" name="tshirt_image_id" value="{{ $tshirtImage->id }}">

            <div>
                <label for="color_code" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                    Cor da T-Shirt
                </label>

                <select
                    name="color_code"
                    id="color_code"
                    required
                    onchange="window.dispatchEvent(new CustomEvent('change-color', { detail: { color: this.value, key: 'catalog-image' } }))"
                    class="w-52 px-1 py-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-md cursor-pointer"
                >
                    <option value="" selected disabled>
                        Selecione uma cor...
                    </option>

                    @foreach($colors as $color)
                        <option value="{{ $color->code }}" {{ old('color_code') == $color->code ? 'selected' : '' }}>
                            {{ $color->name }}
                        </option>
                    @endforeach
                </select>

                @error('color_code')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="size" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                    Tamanho
                </label>

                <select
                    name="size"
                    id="size"
                    required
                    onchange="window.dispatchEvent(new CustomEvent('change-size', { detail: { size: this.value, key: 'catalog-image' } }))"
                    class="w-52 px-1 py-1 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-md cursor-pointer"
                >
                    <option value="" selected disabled>
                        Selecione um tamanho...
                    </option>

                    @foreach($sizes as $size)
                        <option value="{{ $size }}" {{ old('size') == $size ? 'selected' : '' }}>
                            Tamanho {{ $size }}
                        </option>
                    @endforeach
                </select>

                @error('size')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <livewire:counter />

            <div class="pt-2">
                <div class="rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-4">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Preço unitário:
                    </p>

                    <p class="text-2xl font-black text-zinc-900 dark:text-white">
                        {{ number_format($price->unit_price_catalog, 2) }} €
                    </p>

                    @if($price->qty_discount)
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                            A partir de {{ $price->qty_discount }} unidades pode aplicar preço com desconto.
                        </p>
                    @endif
                </div>
            </div>

            <div class="pt-4">
                <flux:button type="submit"
                    variant="primary"
                    icon="shopping-cart"
                    class="w-full justify-center py-3 text-base font-bold cursor-pointer">
                    Adicionar ao Carrinho
                </flux:button>
            </div>
        </form>
    </div>
</div>

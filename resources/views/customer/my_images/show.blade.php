<x-layouts::main-content
    :title="$my_image->name"
    heading="Usar Imagem Personalizada"
    subheading="Escolha a cor, tamanho e quantidade para adicionar ao carrinho">

    <div class="p-6">

        <div class="mb-6">
            <flux:button href="{{ route('my_images.index') }}" icon="arrow-left" variant="ghost">
                Voltar às minhas imagens
            </flux:button>
        </div>

        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">

            <div
                class="w-full max-w-md aspect-square bg-zinc-100 dark:bg-zinc-950 rounded-xl flex items-center justify-center p-4 overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                @php
                    $designUrl = $my_image->id ? route('my_images.file', $my_image->id) . '?v=' . $my_image->updated_at->timestamp : null;
                @endphp

                @if($designUrl)
                    <x-tshirt-preview
                        :backgroundColor="old('color_code', 'ffffff')"
                        :designUrl="$designUrl"
                        :alt="$my_image->name ?? 'T-shirt'"
                        :size="old('size', 'M')"
                        :isCart="false"
                        :isDetail="false"
                        cartKey="own-image"
                        class="w-full h-full"/>
                @else
                    <div class="text-zinc-400 text-sm">Imagem não encontrada</div>
                @endif
            </div>
            <div class="flex flex-col justify-between">

                <div>
                    <div class="mb-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 mb-2 w-fit">
                            Imagem personalizada
                        </span>

                        <h1 class="text-2xl font-extrabold text-zinc-900 dark:text-white">
                            {{ $my_image->name }}
                        </h1>

                        <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-2">
                            {{ $my_image->description ?? 'Esta imagem personalizada pode ser aplicada numa t-shirt à sua escolha.' }}
                        </p>
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-700 my-4">

                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-5">
                        @csrf

                        <input type="hidden" name="tshirt_image_id" value="{{ $my_image->id }}">

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
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <livewire:counter/>
                        </div>

                        @if($price)
                            <div
                                class="rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Preço unitário para imagem personalizada:
                                </p>

                                <p class="text-xl font-black text-zinc-900 dark:text-white">
                                    {{ number_format($price->unit_price_own, 2) }} €
                                </p>

                                @if($price->qty_discount)
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                        A partir de {{ $price->qty_discount }} unidades pode aplicar preço com desconto.
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="pt-4">
                            <flux:button type="submit"
                                         variant="primary"
                                         icon="shopping-cart"
                                         class="w-full justify-center">
                                Adicionar ao Carrinho
                            </flux:button>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </div>

</x-layouts::main-content>

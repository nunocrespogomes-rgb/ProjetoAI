<x-layouts::main-content :title="$tshirtImage->name"
                         heading="Detalhes da T-Shirt"
                         subheading="Personalize o seu tamanho, cor e quantidade para esta estampa">

    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6">

        <div class="mb-2">
            <flux:button href="{{ route('catalog.index') }}" icon="arrow-left" variant="ghost">
                Voltar ao Catálogo
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm">

            <div class="flex flex-col items-center justify-center bg-zinc-100 dark:bg-zinc-950 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 min-h-[400px]">
                @if($tshirtImage->image_url)
                    <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}"
                         class="max-h-[350px] object-contain rounded-lg drop-shadow-xl"
                         alt="{{ $tshirtImage->name }}">
                @else
                    <span class="text-zinc-400 dark:text-zinc-500">Sem imagem disponível</span>
                @endif
            </div>

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

                    <form action="#" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="tshirt_image_id" value="{{ $tshirtImage->id }}">

                        <div>
                            <label for="color_code" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                Cor da T-Shirt
                            </label>
                            <select name="color_code" id="color_code" required
                                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Selecione uma cor...</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->code }}">
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="size" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                Tamanho
                            </label>
                            <select name="size" id="size" required
                                    class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @foreach($sizes as $size)
                                    <option value="{{ $size }}" {{ $size == 'M' ? 'selected' : '' }}>
                                        Tamanho {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-32">
                            <label for="qty" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                                Quantidade
                            </label>
                            <input type="number" name="qty" id="qty" min="1" max="100" value="1" required
                                   class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-center">
                        </div>

                        <div class="pt-2">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Preço unitário estimado:</p>
                            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">15.00 €</p>
                        </div>

                        <div class="pt-4">
                            <flux:button type="submit" variant="primary" icon="shopping-cart" class="w-full justify-center py-3 text-base font-bold">
                                Adicionar ao Carrinho
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-layouts::main-content>


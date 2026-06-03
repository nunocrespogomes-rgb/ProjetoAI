<div class="flex gap-4 w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">

    <div class="h-24 w-24 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
        @if($item['image_url'])
            @if(isset($item['customer_id']) && $item['customer_id'] !== null)
                <img src="{{ route('my_images.file', ['my_image' => $item['tshirt_image_id']]) }}"
                     class="h-full w-full object-contain"
                     alt="{{ $item['name'] }}">
            @else
                <img src="{{ asset('storage/tshirt_images/' . $item['image_url']) }}"
                     class="h-full w-full object-contain"
                     alt="{{ $item['name'] }}">
            @endif
        @else
            <span class="text-xs text-zinc-400">
                Sem imagem
            </span>
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

        <details class="mt-3">
            <summary class="inline-flex cursor-pointer items-center rounded-md bg-zinc-100 px-3 py-1.5 text-xs font-semibold text-zinc-700 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700">
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
                                <option value="{{ $size }}" {{ $item['size'] === $size ? 'selected' : '' }}>
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
                                <option value="{{ $color->code }}" {{ $item['color_code'] === $color->code ? 'selected' : '' }}>
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <livewire:counterCartEdit
                        :qty="$item['qty']"
                        input-name="qty"
                        :key="'counter-'.$cartKey" />

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

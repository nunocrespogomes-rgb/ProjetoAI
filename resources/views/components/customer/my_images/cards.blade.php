<div class="flex flex-col bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden h-full">

    <div class="bg-zinc-100 dark:bg-zinc-950 p-4 flex items-center justify-center w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">
        @if($image->image_url)
            <img src="{{ route('my_images.file', $image) }}?v={{ $image->updated_at->timestamp }}"
                 class="h-full w-full object-contain rounded transition-transform duration-300 hover:scale-105"
                 alt="{{ $image->name }}">
        @else
            <span class="text-zinc-400 dark:text-zinc-500 text-xs">
                Sem Imagem
            </span>
        @endif
    </div>

    <div class="p-4 flex flex-col flex-1">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1"
            title="{{ $image->name }}">
            {{ $image->name }}
        </h3>

        <span class="inline-flex items-center py-0.5  rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 mb-2">
            Imagem personalizada
        </span>

        <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 flex-1 mb-4">
            {{ $image->description ?? 'Sem descrição disponível.' }}
        </p>

        <div class="grid grid-cols-1 gap-2 mt-auto">
            <flux:button href="{{ route('my_images.show', $image) }}"
                         variant="filled"
                         class="w-full justify-center">
                Usar na T-shirt
            </flux:button>

            <flux:button href="{{ route('my_images.edit', $image) }}"
                         variant="ghost"
                         class="w-full justify-center">
                Editar
            </flux:button>

            <form action="{{ route('my_images.destroy', $image) }}"
                  method="POST"
                  onsubmit="return confirm('Tem a certeza que pretende remover esta imagem?');">
                @csrf
                @method('DELETE')

                <flux:button type="submit"
                             variant="danger"
                             class="w-full justify-center">
                    Remover
                </flux:button>
            </form>
        </div>
    </div>

</div>

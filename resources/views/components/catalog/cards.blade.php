<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-6 font-base text-sm">
    @foreach($tshirtImages as $image)
    <div class="flex flex-col bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden h-full">

        <div class="bg-zinc-100 dark:bg-zinc-950 p-2 flex items-center justify-center h-72 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">
            @if($image->image_url)
            <x-tshirt-preview
                backgroundColor="ffffff"
                :designUrl="asset('storage/tshirt_images/' . $image->image_url)"
                :alt="$image->name"
                :scaleUp="true"
                class="w-full h-full" />
            @else
            <span class="text-zinc-400 dark:text-zinc-500 text-xs">
                Sem Imagem
            </span>
            @endif
        </div>

        <div class="p-4 flex flex-col flex-1 bg-white dark:bg-zinc-900">
            <h3 class="font-bold text-zinc-900 dark:text-white text-base truncate mb-1"
                title="{{ $image->name }}">
                {{ $image->name }}
            </h3>

            <div class="mb-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                    {{ $image->category->name ?? 'Geral' }}
                </span>
            </div>

            <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 flex-1 mb-4">
                {{ $image->description ?? 'Sem descrição disponível.' }}
            </p>

            <div class="mt-auto">
                <flux:button href="{{ route('catalog.show', $image->id) }}"
                    variant="filled"
                    class="w-full justify-center cursor-pointer">
                    Ver Detalhes / Preview
                </flux:button>
            </div>
        </div>

    </div>
    @endforeach
</div>
<div class="flex flex-col items-center justify-center bg-zinc-100 dark:bg-zinc-950 p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 w-full aspect-square max-w-md mx-auto">
    @if($tshirtImage->image_url)
        <img src="{{ asset('storage/tshirt_images/' . $tshirtImage->image_url) }}"
             class="w-full h-full max-h-96 object-contain rounded-lg drop-shadow-xl"
             alt="{{ $tshirtImage->name }}">
    @else
        <span class="text-zinc-400 dark:text-zinc-500">
            Sem imagem disponível
        </span>
    @endif
</div>
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

            <x-catalog.image-preview :tshirtImage="$tshirtImage" />

            <x-catalog.details
                :tshirtImage="$tshirtImage"
                :colors="$colors"
                :sizes="$sizes"
                :price="$price"
            />

        </div>
    </div>

</x-layouts::main-content>




















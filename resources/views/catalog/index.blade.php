<x-layouts::main-content title="Catálogo"
                         heading="Catálogo de T-Shirts"
                         subheading="Explore os nossos designs públicos disponíveis para estampagem">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-start">
            <div class="my-4 p-6 w-full">

                <x-catalog.filters :categories="$categories" />

                @if($tshirtImages->isEmpty())
                    <div
                        class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 text-center"
                        role="alert">
                        Não foram encontradas t-shirts públicas com os filtros selecionados.
                    </div>
                @else
                    <x-catalog.cards :tshirtImages="$tshirtImages" />

                    <div class="mt-6">
                        {{ $tshirtImages->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layouts::main-content>





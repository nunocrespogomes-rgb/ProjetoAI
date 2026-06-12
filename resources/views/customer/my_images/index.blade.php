

<x-layouts::main-content
    title="Minhas Imagens"
    heading="Minhas Imagens Personalizadas"
    subheading="Gere as suas imagens pessoais para usar em t-shirts personalizadas">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex justify-start">
            <div class="my-4 p-6 w-full">

                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                            Biblioteca pessoal
                        </h2>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                            Estas imagens são exclusivas da sua conta.
                        </p>
                    </div>

                    <flux:button href="{{ route('my_images.create') }}" variant="primary" icon="plus">
                        Adicionar Imagem
                    </flux:button>
                </div>

                @if($my_images->isEmpty())
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center">
                        <p class="text-zinc-500 dark:text-zinc-400">
                            Ainda não adicionou imagens personalizadas.
                        </p>

                        <div class="mt-4">
                            <flux:button href="{{ route('my_images.create') }}" variant="primary">
                                Adicionar primeira imagem
                            </flux:button>
                        </div>
                    </div>
                @else
                    <x-customer.my_images.grid :myImages="$my_images" />

                    <div class="mt-6">
                        {{ $my_images->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-layouts::main-content>

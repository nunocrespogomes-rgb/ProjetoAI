{{--<x-layouts::main-content--}}
{{--    title="Minhas Imagens"--}}
{{--    heading="Minhas Imagens Personalizadas"--}}
{{--    subheading="Gere as suas imagens pessoais para usar em t-shirts personalizadas">--}}

{{--    <div class="p-6">--}}

{{--        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">--}}
{{--            <div>--}}
{{--                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">--}}
{{--                    Biblioteca pessoal--}}
{{--                </h2>--}}
{{--                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">--}}
{{--                    Estas imagens são exclusivas da sua conta.--}}
{{--                </p>--}}
{{--            </div>--}}

{{--            <flux:button href="{{ route('my_images.create') }}" variant="primary" icon="plus">--}}
{{--                Adicionar Imagem--}}
{{--            </flux:button>--}}
{{--        </div>--}}

{{--        @if($my_images->isEmpty())--}}

{{--            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center">--}}
{{--                <p class="text-zinc-500 dark:text-zinc-400">--}}
{{--                    Ainda não adicionou imagens personalizadas.--}}
{{--                </p>--}}

{{--                <div class="mt-4">--}}
{{--                    <flux:button href="{{ route('my_images.create') }}" variant="primary">--}}
{{--                        Adicionar primeira imagem--}}
{{--                    </flux:button>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        @else--}}

{{--            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">--}}

{{--                @foreach($my_images as $image)--}}

{{--                    <div class="flex flex-col bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">--}}

{{--                        <div class="bg-zinc-100 dark:bg-zinc-950 p-4 flex items-center justify-center  w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">--}}
{{--                            @if($image->image_url)--}}
{{--                                <img src="{{ route('my_images.file', $image) }}"--}}
{{--                                     class="h-full w-full object-contain rounded transition-transform duration-300 hover:scale-105"--}}
{{--                                     alt="{{ $image->name }}">--}}
{{--                            @else--}}
{{--                                <span class="text-zinc-400 dark:text-zinc-500 text-xs">--}}
{{--                                    Sem Imagem--}}
{{--                                </span>--}}
{{--                            @endif--}}
{{--                        </div>--}}

{{--                        <div class="p-4 flex flex-col flex-1">--}}
{{--                            <h3 class=" text-lg font-bold text-zinc-900 dark:text-white  mb-1"--}}
{{--                                title="{{ $image->name }}">--}}
{{--                                {{ $image->name }}--}}
{{--                            </h3>--}}

{{--                            <span class="inline-flex w-fit items-center  py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 mb-2">--}}
{{--                                Imagem personalizada--}}
{{--                            </span>--}}

{{--                            <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 flex-1 mb-4">--}}
{{--                                {{ $image->description ?? 'Sem descrição disponível.' }}--}}
{{--                            </p>--}}

{{--                            <div class="grid grid-cols-1 gap-2 mt-auto">--}}
{{--                                <flux:button href="{{ route('my_images.show', $image) }}"--}}
{{--                                             variant="filled"--}}
{{--                                             class="w-full justify-center">--}}
{{--                                    Usar na T-shirt--}}
{{--                                </flux:button>--}}

{{--                                <flux:button href="{{ route('my_images.edit', $image) }}"--}}
{{--                                             variant="ghost"--}}
{{--                                             class="w-full justify-center">--}}
{{--                                    Editar--}}
{{--                                </flux:button>--}}

{{--                                <form action="{{ route('my_images.destroy', $image) }}"--}}
{{--                                      method="POST"--}}
{{--                                      onsubmit="return confirm('Tem a certeza que pretende remover esta imagem?');">--}}
{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}

{{--                                    <flux:button type="submit"--}}
{{--                                                 variant="danger"--}}
{{--                                                 class="w-full justify-center">--}}
{{--                                        Remover--}}
{{--                                    </flux:button>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}

{{--                @endforeach--}}

{{--            </div>--}}

{{--            <div class="mt-6">--}}
{{--                {{ $my_images->links() }}--}}
{{--            </div>--}}

{{--        @endif--}}

{{--    </div>--}}

{{--</x-layouts::main-content>--}}

<x-layouts::main-content
    title="Minhas Imagens"
    heading="Minhas Imagens Personalizadas"
    subheading="Gere as suas imagens pessoais para usar em t-shirts personalizadas">

    <style>
        .p-4.flex.flex-col.flex-1 span,
        [class*="bg-indigo-100"] {
            display: inline-flex !important;
            width: fit-content !important;
            max-width: max-content !important;
            padding-left: 0.625rem !important;
            padding-right: 0.625rem !important;
            align-items: center;
        }
    </style>
    
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

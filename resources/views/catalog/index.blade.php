{{--<x-layouts::main-content title="Catálogo"--}}
{{--                         heading="Catálogo de T-Shirts"--}}
{{--                         subheading="Explore os nossos designs públicos disponíveis para estampagem">--}}

{{--    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">--}}
{{--        <div class="flex justify-start">--}}
{{--            <div class="my-4 p-6 w-full">--}}

{{--                <div--}}
{{--                    class="mb-6 card p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">--}}
{{--                    <form action="{{ route('catalog.index') }}" method="GET"--}}
{{--                          class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">--}}

{{--                        <div>--}}
{{--                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>--}}
{{--                            <input type="text" name="search" id="search"--}}
{{--                                   class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 "--}}
{{--                                   placeholder="Nome ou descrição..." value="{{ request('search') }}">--}}
{{--                        </div>--}}

{{--                        <div>--}}
{{--                            <label for="category"--}}
{{--                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>--}}
{{--                            <select name="category" id="category"--}}
{{--                                    class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">--}}
{{--                                <option value="">Todas as Categorias</option>--}}
{{--                                @foreach($categories as $category)--}}
{{--                                    <option--}}
{{--                                        value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>--}}
{{--                                        {{ $category->name }}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="flex gap-2 py-3">--}}
{{--                            <flux:button type="submit" variant="primary" class="w-full justify-center">Filtrar--}}
{{--                            </flux:button>--}}
{{--                            <flux:button href="{{ route('catalog.index') }}" variant="filled"--}}
{{--                                         class="w-full justify-center">Limpar--}}
{{--                            </flux:button>--}}
{{--                        </div>--}}

{{--                    </form>--}}
{{--                </div>--}}

{{--                @if($tshirtImages->isEmpty())--}}
{{--                    <div--}}
{{--                        class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 text-center"--}}
{{--                        role="alert">--}}
{{--                        Não foram encontradas t-shirts públicas com os filtros selecionados.--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                                        <div--}}
{{--                                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-6 font-base text-sm">--}}
{{--                                            @foreach($tshirtImages as $image)--}}
{{--                                                <div--}}
{{--                                                    class="flex flex-col bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden h-full">--}}

{{--                                                    <div--}}
{{--                                                        class="bg-zinc-100 dark:bg-zinc-950 p-4 flex items-center justify-center h-64 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">--}}
{{--                                                        @if($image->image_url)--}}
{{--                                                            <img src="{{ asset('storage/tshirt_images/' . $image->image_url) }}"--}}
{{--                                                                 class="max-h-full max-w-full object-contain rounded transition-transform duration-300 hover:scale-105"--}}
{{--                                                                 alt="{{ $image->name }}">--}}
{{--                                                        @else--}}
{{--                                                            <span class="text-zinc-400 dark:text-zinc-500 text-xs">Sem Imagem</span>--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}
{{--                                                    <div--}}
{{--                                                        class="bg-zinc-100 dark:bg-zinc-950 p-2 flex items-center justify-center h-72 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">--}}
{{--                                                        @if($image->image_url)--}}
{{--                                                            <img src="{{ asset('storage/tshirt_images/' . $image->image_url) }}"--}}
{{--                                                                 class="h-full w-auto object-center object-contain rounded transition-transform duration-300 hover:scale-105"--}}
{{--                                                                 style="max-width: 100%;"--}}
{{--                                                                 alt="{{ $image->name }}">--}}
{{--                                                        @else--}}
{{--                                                            <span class="text-zinc-400 dark:text-zinc-500 text-xs">Sem Imagem</span>--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}

{{--                                                    <div class="p-4 flex flex-col flex-1 bg-white dark:bg-zinc-900">--}}
{{--                                                        <h3 class="font-bold text-zinc-900 dark:text-white text-base truncate mb-1"--}}
{{--                                                            title="{{ $image->name }}">--}}
{{--                                                            {{ $image->name }}--}}
{{--                                                        </h3>--}}

{{--                                                        <div class="mb-2">--}}
{{--                                                    <span--}}
{{--                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">--}}
{{--                                                        {{ $image->category->name ?? 'Geral' }}--}}
{{--                                                    </span>--}}
{{--                                                        </div>--}}

{{--                                                        <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 flex-1 mb-4">--}}
{{--                                                            {{ $image->description ?? 'Sem descrição disponível.' }}--}}
{{--                                                        </p>--}}

{{--                                                        <div class="mt-auto">--}}
{{--                                                            <flux:button href="{{ route('catalog.show', $image->id) }}" variant="filled"--}}
{{--                                                                         class="w-full justify-center">--}}
{{--                                                                Ver Detalhes / Preview--}}
{{--                                                            </flux:button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}

{{--                                        <div class="mt-6">--}}
{{--                                            {{ $tshirtImages->links() }}--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--</x-layouts::main-content>--}}

{{--<x-layouts::main-content title="Catálogo"--}}
{{--                         heading="Catálogo de T-Shirts"--}}
{{--                         subheading="Explore os nossos designs públicos disponíveis para estampagem">--}}

{{--    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">--}}
{{--        <div class="flex justify-start">--}}
{{--            <div class="my-4 p-6 w-full">--}}

{{--                <div--}}
{{--                    class="mb-6  p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">--}}
{{--                    <form action="{{ route('catalog.index') }}" method="GET"--}}
{{--                          class="grid grid-cols-1 md:grid-cols-3 gap-4">--}}

{{--                        <div>--}}
{{--                            <label for="search"--}}
{{--                                   class=" text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesquisar</label>--}}
{{--                            <input type="text" name="search" id="search"--}}
{{--                                   class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 "--}}
{{--                                   placeholder="Nome ou descrição..."--}}
{{--                                   value="{{ request('search') }}">--}}
{{--                        </div>--}}

{{--                        <div>--}}
{{--                            <label for="category"--}}
{{--                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>--}}
{{--                            <select name="category" id="category"--}}
{{--                                    class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">--}}
{{--                                <option value="">Todas as Categorias</option>--}}
{{--                                @foreach($categories as $category)--}}
{{--                                    <option--}}
{{--                                        value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>--}}
{{--                                        {{ $category->name }}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="flex gap-2 py-3">--}}
{{--                            <flux:button type="submit" variant="primary"--}}
{{--                                         class="w-full justify-center">Filtrar--}}
{{--                            </flux:button>--}}
{{--                            <flux:button href="{{ route('catalog.index') }}" variant="filled"--}}
{{--                                         class="w-full justify-center">Limpar--}}
{{--                            </flux:button>--}}
{{--                        </div>--}}

{{--                    </form>--}}
{{--                </div>--}}

{{--                @if($tshirtImages->isEmpty())--}}
{{--                    <div--}}
{{--                        class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 text-center"--}}
{{--                        role="alert">--}}
{{--                        Não foram encontradas t-shirts públicas com os filtros selecionados.--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div--}}
{{--                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-6 font-base text-sm">--}}
{{--                        @foreach($tshirtImages as $image)--}}
{{--                            <div--}}
{{--                                class="flex flex-col bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden h-full">--}}

{{--                                <div--}}
{{--                                    class="bg-zinc-100 dark:bg-zinc-950 p-4 flex items-center justify-center h-64 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">--}}
{{--                                    @if($image->image_url)--}}
{{--                                        <img--}}
{{--                                            src="{{ asset('storage/tshirt_images/' . $image->image_url) }}"--}}
{{--                                            class="max-h-full max-w-full object-contain rounded transition-transform duration-300 hover:scale-105"--}}
{{--                                            alt="{{ $image->name }}">--}}
{{--                                    @else--}}
{{--                                        <span class="text-zinc-400 dark:text-zinc-500 text-xs">Sem Imagem</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div--}}
{{--                                    class="bg-zinc-100 dark:bg-zinc-950 p-2 flex items-center justify-center h-72 w-full overflow-hidden border-b border-zinc-200 dark:border-zinc-700">--}}
{{--                                    @if($image->image_url)--}}
{{--                                        <img src="{{ asset('storage/tshirt_images/' . $image->image_url) }}"--}}
{{--                                             class="h-full w-auto object-center object-contain rounded transition-transform duration-300 hover:scale-105"--}}
{{--                                             style="max-width: 100%;"--}}
{{--                                             alt="{{ $image->name }}">--}}
{{--                                    @else--}}
{{--                                        <span class="text-zinc-400 dark:text-zinc-500 text-xs">Sem Imagem</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                <div class="p-4 flex flex-col flex-1 bg-white dark:bg-zinc-900">--}}
{{--                                    <h3 class="font-bold text-zinc-900 dark:text-white text-base truncate mb-1"--}}
{{--                                        title="{{ $image->name }}">--}}
{{--                                        {{ $image->name }}--}}
{{--                                    </h3>--}}

{{--                                    <div class="mb-2">--}}
{{--                                <span--}}
{{--                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">--}}
{{--                                    {{ $image->category->name ?? 'Geral' }}--}}
{{--                                </span>--}}
{{--                                    </div>--}}

{{--                                    <p class="text-zinc-500 dark:text-zinc-400 text-xs line-clamp-2 flex-1 mb-4">--}}
{{--                                        {{ $image->description ?? 'Sem descrição disponível.' }}--}}
{{--                                    </p>--}}

{{--                                    <div class="mt-auto">--}}
{{--                                        <flux:button href="{{ route('catalog.show', $image->id) }}"--}}
{{--                                                     variant="filled"--}}
{{--                                                     class="w-full justify-center">--}}
{{--                                            Ver Detalhes / Preview--}}
{{--                                        </flux:button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}

{{--                    <div class="mt-6">--}}
{{--                        {{ $tshirtImages->links() }}--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--</x-layouts::main-content>--}}



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





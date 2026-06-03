<div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <form action="{{ route('catalog.index') }}" method="GET"
          class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <label for="search"
                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Pesquisar
            </label>

            <input type="text"
                   name="search"
                   id="search"
                   class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Nome ou descrição..."
                   value="{{ request('search') }}">
        </div>

        <div>
            <label for="category"
                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Categoria
            </label>

            <select name="category"
                    id="category"
                    class="w-full text-md px-3 py-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todas as Categorias</option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2 py-3">
            <flux:button type="submit"
                         variant="primary"
                         class="w-full justify-center">
                Filtrar
            </flux:button>

            <flux:button href="{{ route('catalog.index') }}"
                         variant="filled"
                         class="w-full justify-center">
                Limpar
            </flux:button>
        </div>

    </form>
</div>

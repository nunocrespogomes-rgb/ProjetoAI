<x-layouts::main-content
    title="Editar T-shirt"
    subheading="Atualiza os dados desta imagem do catálogo."
>
    <div class="p-6 lg:p-8 max-w-3xl space-y-6">

        <div>
            <flux:button
                icon="arrow-left"
                variant="ghost"
                :href="route('admin.tshirts.index')"
                wire:navigate
            >
                Voltar ao catálogo
            </flux:button>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900 p-6">

            {{-- Pré-visualização --}}
            @if($tshirt->image_url)
                <div class="mb-6 flex justify-center">
                    <img
                        src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                        alt="{{ $tshirt->name }}"
                        class="h-48 w-48 rounded-xl border border-zinc-200 bg-white object-contain dark:border-zinc-700"
                        onerror="this.style.display='none';"
                    >
                </div>
            @endif

            <form
                action="{{ route('admin.tshirts.update', $tshirt) }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5"
            >
                @csrf
                @method('PUT')

                {{-- Nome --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $tshirt->name) }}"
                        required
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descrição --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Descrição
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                    >{{ old('description', $tshirt->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Categoria --}}
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="category_id"
                        id="category_id"
                        required
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                    >
                        <option value="">-- Seleciona uma categoria --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $tshirt->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Substituir imagem --}}
                <div>
                    <label for="image_file" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Substituir imagem
                    </label>
                    <input
                        type="file"
                        name="image_file"
                        id="image_file"
                        accept="image/png,image/jpeg,image/webp"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3 py-2"
                    >
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Deixe vazio para manter a imagem atual. Máximo 2 MB.
                    </p>
                    @error('image_file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <flux:button type="submit" variant="primary">
                        Guardar Alterações
                    </flux:button>
                    <flux:button :href="route('admin.tshirts.index')" variant="ghost" wire:navigate>
                        Cancelar
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::main-content>

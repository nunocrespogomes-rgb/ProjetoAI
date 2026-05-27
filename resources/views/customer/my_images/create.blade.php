<x-layouts::main-content
    title="Adicionar Imagem"
    heading="Adicionar Imagem Personalizada"
    subheading="Envie uma imagem própria para usar nas suas t-shirts">

    <div class="p-6 max-w-3xl">

        <div class="mb-6">
            <flux:button href="{{ route('customer.tshirt-images.index') }}" icon="arrow-left" variant="ghost">
                Voltar às minhas imagens
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

            <form action="{{ route('customer.tshirt-images.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Nome da imagem
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                           placeholder="Ex: Logo da equipa">

                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Descrição
                    </label>

                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2"
                              placeholder="Descrição opcional da imagem">{{ old('description') }}</textarea>

                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Ficheiro da imagem
                    </label>

                    <input type="file"
                           name="image"
                           id="image"
                           required
                           accept="image/png,image/jpeg,image/webp"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3 py-2">

                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Formatos aceites: JPG, PNG ou WEBP. Máximo: 4MB.
                    </p>

                    @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <flux:button type="submit" variant="primary" class="w-full sm:w-auto justify-center">
                        Guardar Imagem
                    </flux:button>

                    <flux:button href="{{ route('customer.tshirt-images.index') }}"
                                 variant="ghost"
                                 class="w-full sm:w-auto justify-center">
                        Cancelar
                    </flux:button>
                </div>

            </form>

        </div>

    </div>

</x-layouts::main-content>

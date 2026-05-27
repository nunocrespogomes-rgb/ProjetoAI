<x-layouts::main-content
    title="Editar Imagem"
    heading="Editar Imagem Personalizada"
    subheading="Atualize os dados da sua imagem privada">

    <div class="p-6 max-w-3xl">

        <div class="mb-6">
            <flux:button href="{{ route('customer.tshirt-images.index') }}" icon="arrow-left" variant="ghost">
                Voltar às minhas imagens
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

            <div class="mb-6 bg-zinc-100 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 flex justify-center h-[260px]">
                @if($tshirtImage->image_url)
                    <img src="{{ route('customer.tshirt-images.file', $tshirtImage) }}"
                         class="h-full w-full object-contain rounded"
                         alt="{{ $tshirtImage->name }}">
                @else
                    <span class="text-zinc-400 dark:text-zinc-500 text-sm">
                        Sem imagem
                    </span>
                @endif
            </div>

            <form action="{{ route('customer.tshirt-images.update', $tshirtImage) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Nome da imagem
                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $tshirtImage->name) }}"
                           required
                           class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">

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
                              class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('description', $tshirtImage->description) }}</textarea>

                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                        Substituir imagem
                    </label>

                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/png,image/jpeg,image/webp"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3 py-2">

                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        Deixe vazio para manter a imagem atual.
                    </p>

                    @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <flux:button type="submit" variant="primary" class="w-full sm:w-auto justify-center">
                        Guardar Alterações
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

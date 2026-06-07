<x-layouts::main-content
    title="Editar Imagem"
    heading="Editar Imagem Personalizada"
    subheading="Atualize os dados da sua imagem privada">

    <div class="p-6 max-w-3xl">

        <div class="mb-6">
            <flux:button href="{{ route('my_images.index') }}" icon="arrow-left" variant="ghost">
                Voltar às minhas imagens
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">

            <div id="tshirt-preview-container" class="mb-6 flex flex-col items-center justify-center bg-zinc-100 dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-700 aspect-square max-w-md mx-auto w-full overflow-hidden">
                @if($my_image->image_url)
                <x-tshirt-preview
                    :backgroundColor="'1e1e21'"
                    :designUrl="route('my_images.file', $my_image) . '?v=' . $my_image->updated_at->timestamp"
                    :alt="$my_image->name"
                    :size="'M'"
                    :cartKey="'own-image-edit'"
                    :isCart="false"
                    class="w-full h-full" />
                @else
                <span class="text-zinc-400 dark:text-zinc-500 text-sm">
                    Sem imagem
                </span>
                @endif
            </div>

            <form action="{{ route('my_images.update', $my_image) }}"
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
                        value="{{ old('name', $my_image->name) }}"
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
                        class="w-full rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">{{ old('description', $my_image->description) }}</textarea>

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
                        id="image-input"
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

                    <flux:button href="{{ route('my_images.index') }}"
                        variant="ghost"
                        class="w-full sm:w-auto justify-center">
                        Cancelar
                    </flux:button>
                </div>

            </form>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image-input');
            const previewContainer = document.getElementById('tshirt-preview-container');

            if (imageInput && previewContainer) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            // Tenta encontrar a tag <img> correspondente à estampa dentro do componente da T-shirt
                            // Normalmente o componente renderiza a t-shirt de fundo e uma segunda imagem com a estampa por cima.
                            // Procuramos a imagem que tem o 'route(my_images.file)' ou a estampa
                            const images = previewContainer.querySelectorAll('img');

                            if (images.length > 0) {
                                // Se o teu componente renderiza duas imagens (1ª t-shirt base, 2ª estampa), 
                                // atualizamos a última imagem encontrada que representa o design.
                                const designImage = images[images.length - 1];
                                designImage.src = event.target.result;
                            }
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>

</x-layouts::main-content>
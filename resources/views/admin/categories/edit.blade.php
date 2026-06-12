<x-layouts::main-content
    title="Editar categoria"
    subheading="Atualiza o nome e a imagem da categoria."
>
    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="flex justify-end">
                <flux:button icon="arrow-left" :href="route('admin.categories.index')" wire:navigate>
                    Voltar
                </flux:button>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if($category->image_url)
                        <div>
                            <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Imagem atual
                            </label>

                            <img
                                src="{{ asset('storage/categories/' . $category->image_url) }}"
                                alt="{{ $category->name }}"
                                class="h-28 w-28 rounded-xl border border-zinc-200 object-cover dark:border-zinc-700"
                            >
                        </div>
                    @endif

                    <div>
                        <flux:input
                            name="name"
                            label="Nome"
                            value="{{ old('name', $category->name) }}"
                            required
                        />
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            type="file"
                            name="image_file"
                            label="Nova imagem da categoria"
                            accept="image/*"
                        />
                        @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-sm text-zinc-500">
                            Se escolheres uma nova imagem, a imagem atual será substituída.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <flux:button :href="route('admin.categories.index')" wire:navigate>
                            Cancelar
                        </flux:button>

                        <flux:button type="submit" variant="primary" icon="check">
                            Guardar alterações
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::main-content>

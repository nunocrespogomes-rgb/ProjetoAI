<x-layouts::main-content
    title="Nova categoria"
    subheading="Adiciona uma categoria para organizar as imagens do catálogo."
>
    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="flex justify-end">
                <flux:button icon="arrow-left" :href="route('admin.categories.index')" wire:navigate>
                    Voltar
                </flux:button>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <flux:input
                            name="name"
                            label="Nome"
                            value="{{ old('name') }}"
                            required
                        />
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            type="file"
                            name="image"
                            label="Imagem da categoria"
                            accept="image/*"
                        />
                        @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-sm text-zinc-500">
                            Esta imagem é opcional e será usada para representar visualmente a categoria no catálogo.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <flux:button :href="route('admin.categories.index')" wire:navigate>
                            Cancelar
                        </flux:button>

                        <flux:button type="submit" variant="primary" icon="check">
                            Guardar categoria
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::main-content>

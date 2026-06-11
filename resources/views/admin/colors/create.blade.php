<x-layouts::main-content
    title="Nova cor"
    subheading="Adiciona uma cor disponível para venda e a imagem base da t-shirt."
>
    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="flex justify-end">
                <flux:button icon="arrow-left" :href="route('admin.colors.index')" wire:navigate>
                    Voltar
                </flux:button>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('admin.colors.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <flux:input
                                name="code"
                                label="Código CSS da cor"
                                value="{{ old('code') }}"
                                placeholder="white, black ou #7fffd4"
                                required
                            />
                            @error('code')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-zinc-500">
                                Este valor é a chave primária da cor.
                            </p>
                        </div>

                        <div>
                            <flux:input
                                name="name"
                                label="Nome visível"
                                value="{{ old('name') }}"
                                placeholder="Branco, Preto, Azul..."
                                required
                            />
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <flux:input
                            type="file"
                            name="base_image"
                            label="Imagem base da t-shirt"
                            accept="image/*"
                        />
                        @error('base_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <p class="mt-2 text-sm text-zinc-500">
                            Deve representar a t-shirt vazia desta cor. Será guardada em
                            <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-xs dark:bg-zinc-800">
                                storage/app/public/tshirt_base
                            </code>.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <flux:button :href="route('admin.colors.index')" wire:navigate>
                            Cancelar
                        </flux:button>

                        <flux:button type="submit" variant="primary" icon="check">
                            Guardar cor
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::main-content>

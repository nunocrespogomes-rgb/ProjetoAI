<x-layouts::main-content
    title="Editar cor"
    subheading="Atualiza o nome e a imagem base da t-shirt."
>
    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-3xl space-y-6">
            <div class="flex justify-end">
                <flux:button icon="arrow-left" :href="route('admin.colors.index')" wire:navigate>
                    Voltar
                </flux:button>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('admin.colors.update', $color) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-4 rounded-xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                        <div
                            class="h-12 w-12 rounded-full border border-zinc-300 shadow-sm dark:border-zinc-600"
                            @php
                                $cssColor = '#' . $color->code;
                            @endphp
                            style="background-color: {{ $cssColor }}"
                        ></div>

                        <div>
                            <div class="text-sm text-zinc-500">Código da cor</div>
                            <code class="font-medium">{{ $color->code }}</code>
                        </div>
                    </div>

                    <flux:input
                        name="name"
                        label="Nome visível"
                        value="{{ old('name', $color->name) }}"
                        required
                    />
                    @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <flux:input
                        type="file"
                        name="base_image"
                        label="Nova imagem base da t-shirt"
                        accept="image/*"
                    />
                    @error('base_image')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <flux:button :href="route('admin.colors.index')" wire:navigate>
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

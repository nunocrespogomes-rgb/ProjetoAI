<x-layouts::main-content
    title="Cores"
    subheading="Gestão das cores disponíveis para venda e das respetivas bases de t-shirt."
>
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex justify-start">
            <flux:button
                icon="plus"
                variant="primary"
                :href="route('admin.colors.create')"
                wire:navigate
            >
                Nova cor
            </flux:button>
        </div>

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if($colors->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon.paint-brush class="size-8 text-zinc-500" />
                    </div>

                    <flux:heading size="lg">
                        Ainda não existem cores
                    </flux:heading>

                    <flux:text class="mt-2 max-w-md text-zinc-500 dark:text-zinc-400">
                        Cria cores para que os clientes possam escolher a base da t-shirt.
                    </flux:text>

                    <flux:button
                        class="mt-6"
                        variant="primary"
                        icon="plus"
                        :href="route('admin.colors.create')"
                        wire:navigate
                    >
                        Criar primeira cor
                    </flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Amostra
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Nome
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                T-shirt base
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Ações
                            </th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($colors as $color)
                            @php
                                $colorCode = trim($color->code);
                                $baseFile = null;
                                foreach (['png', 'jpg', 'jpeg', 'webp'] as $ext) {
                                    if (file_exists(public_path('storage/tshirt_base/' . $colorCode . '.' . $ext))) {
                                        $baseFile = $colorCode . '.' . $ext;
                                        break;
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                @php
                                    $cssColor = '#' . $color->code;
                                @endphp

                                <td class="px-6 py-4">
                                    <div
                                        class="h-10 w-10 rounded-full border-2 border-zinc-400 shadow-sm ring-1 ring-zinc-200 dark:border-zinc-500 dark:ring-zinc-700"
                                        style="background-color: {{ $cssColor }};"
                                        title="{{ $cssColor }}"
                                    ></div>
                                </td>

                                <td class="px-6 py-4">
                                    <code class="rounded bg-zinc-100 px-2 py-1 text-sm text-zinc-800 dark:bg-zinc-800 dark:text-zinc-100">
                                        {{ $colorCode }}
                                    </code>
                                </td>

                                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $color->name }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($baseFile)
                                            <img
                                                src="{{ asset('storage/tshirt_base/' . $baseFile) }}"
                                                alt="T-shirt {{ $color->name }}"
                                                class="h-14 w-14 rounded-lg border border-zinc-200 bg-white object-contain dark:border-zinc-700"
                                            >
                                        @else
                                            <span class="text-sm text-zinc-500 dark:text-zinc-400">Sem imagem</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <flux:button
                                            size="sm"
                                            icon="pencil-square"
                                            :href="route('admin.colors.edit', $color)"
                                            wire:navigate
                                        >
                                            Editar
                                        </flux:button>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.colors.destroy', $color->code) }}"
                                            onsubmit="return confirm('Tem a certeza que pretende remover esta cor?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <flux:button
                                                size="sm"
                                                variant="danger"
                                                icon="trash"
                                                type="submit"
                                            >
                                                Remover
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($colors, 'links') && $colors->hasPages())
                    <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        {{ $colors->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts::main-content>

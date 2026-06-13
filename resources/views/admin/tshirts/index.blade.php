<x-layouts::main-content
    title="T-shirts"
    subheading="Gestão das imagens de t-shirt disponíveis no catálogo."
>
    <div class="p-6 lg:p-8 space-y-6">

        <div class="flex justify-start">
            @can('create', \App\Models\TshirtImage::class)
                <flux:button
                    icon="plus"
                    variant="primary"
                    :href="route('admin.tshirts.create')"
                    wire:navigate
                >
                    Nova t-shirt
                </flux:button>
            @endcan
        </div>

        {{-- Filtro --}}
        <form method="GET" action="{{ route('admin.tshirts.index') }}" class="flex gap-3">
            <flux:input
                name="search"
                placeholder="Pesquisar por nome ou descrição..."
                value="{{ request('search') }}"
                class="max-w-sm"
            />
            <flux:button type="submit" variant="ghost" icon="magnifying-glass">
                Pesquisar
            </flux:button>
            @if(request('search'))
                <flux:button :href="route('admin.tshirts.index')" variant="ghost" wire:navigate>
                    Limpar
                </flux:button>
            @endif
        </form>

        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if($tshirts->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon.shopping-bag class="size-8 text-zinc-500" />
                    </div>

                    @if(request('search'))
                        <flux:heading size="lg">
                            Nenhuma t-shirt encontrada
                        </flux:heading>
                        <flux:text class="mt-2 max-w-md text-zinc-500 dark:text-zinc-400">
                            Não existem t-shirts que correspondam à pesquisa "{{ request('search') }}".
                        </flux:text>
                        <flux:button
                            class="mt-6"
                            variant="ghost"
                            icon="x-mark"
                            :href="route('admin.tshirts.index')"
                            wire:navigate
                        >
                            Limpar pesquisa
                        </flux:button>
                    @else
                        <flux:heading size="lg">
                            Ainda não existem t-shirts no catálogo
                        </flux:heading>
                        <flux:text class="mt-2 max-w-md text-zinc-500 dark:text-zinc-400">
                            Adiciona imagens ao catálogo para que os clientes possam encomendá-las.
                        </flux:text>
                        @can('create', \App\Models\TshirtImage::class)
                            <flux:button
                                class="mt-6"
                                variant="primary"
                                icon="plus"
                                :href="route('admin.tshirts.create')"
                                wire:navigate
                            >
                                Adicionar primeira t-shirt
                            </flux:button>
                        @endcan
                    @endif
                </div>
            @else
                @php $canManage = auth()->user()->isAdmin() @endphp

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Imagem
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Nome
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Categoria
                            </th>
                            <th class="px-9 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                Tipo
                            </th>
                            @if($canManage)
                                <th class="pr-30 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                    Ações
                                </th>
                            @endif
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @foreach($tshirts as $tshirt)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                <td class="px-6 py-4">
                                    @if($tshirt->image_url)
                                        <img
                                            src="{{ asset('storage/tshirt_images/' . $tshirt->image_url) }}"
                                            alt="{{ $tshirt->name }}"
                                            class="h-14 w-14 rounded-lg border border-zinc-200 bg-white object-contain dark:border-zinc-700"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <div class="hidden h-14 w-14 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                                            <flux:icon.photo class="size-6 text-zinc-400" />
                                        </div>
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                                            <flux:icon.photo class="size-6 text-zinc-400" />
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">
                                        {{ $tshirt->name }}
                                    </p>
                                    @if($tshirt->description)
                                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-1">
                                            {{ $tshirt->description }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $tshirt->category?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($tshirt->customer_id)
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                Personalizada
                                            </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                                Catálogo
                                            </span>
                                    @endif
                                </td>

                                @if($canManage)
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <flux:button
                                                size="sm"
                                                icon="pencil-square"
                                                :href="route('admin.tshirts.edit', $tshirt)"
                                                wire:navigate
                                            >
                                                Editar
                                            </flux:button>

                                            <form
                                                method="POST"
                                                action="{{ route('admin.tshirts.destroy', $tshirt) }}"
                                                onsubmit="return confirm('Tem a certeza que pretende remover esta t-shirt do catálogo?')"
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
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($tshirts->hasPages())
                    <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        {{ $tshirts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts::main-content>

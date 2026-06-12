<x-layouts::main-content :title="__('Categorias')"
subheading="Gestão das categorias usadas para organizar as imagens do catálogo.">
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <flux:button icon="plus" variant="primary" :href="route('admin.categories.create')" wire:navigate>
                Nova categoria
            </flux:button>
        </div>


        <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @if($categories->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                    <div class="mb-4 rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon.tag class="size-8 text-zinc-500" />
                    </div>
                    <flux:heading size="lg">Ainda não existem categorias</flux:heading>
                    <flux:text class="mt-2 max-w-md text-zinc-500 dark:text-zinc-400">
                        Cria categorias para facilitar a pesquisa e filtragem das imagens do catálogo.
                    </flux:text>
                    <flux:button class="mt-6" variant="primary" icon="plus" :href="route('admin.categories.create')" wire:navigate>
                        Criar primeira categoria
                    </flux:button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Imagem</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">Nome</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($categories as $category)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                                    <td class="px-6 py-4">
                                        @if($category->image_url)
                                            <img src="{{ asset('storage/categories/' . $category->image_url) }}"
                                                 alt="{{ $category->name }}"
                                                 class="h-14 w-14 rounded-lg border border-zinc-200 object-cover dark:border-zinc-700">
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-zinc-50 text-xs text-zinc-400 dark:border-zinc-700 dark:bg-zinc-800">
                                                Sem imagem
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <flux:button size="sm" icon="pencil-square" :href="route('admin.categories.edit', $category)" wire:navigate>
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Tem a certeza que pretende remover esta categoria?')">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button size="sm" variant="danger" icon="trash" type="submit">
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

                @if(method_exists($categories, 'links') && $categories->hasPages())
                    <div class="border-t border-zinc-200 px-6 py-4 dark:border-zinc-700">
                        {{ $categories->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts::main-content>

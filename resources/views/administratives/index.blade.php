<x-layouts::main-content title="Administrativos"
                        heading="Lista de administrativos"
                        subheading="Gerir os administrativos da instituição">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <div class="flex justify-start ">
            <div class="my-4 p-6 ">
                <x-administratives.filter-card
                    :filterAction="route('administratives.index')"
                    :resetUrl="route('administratives.index')"
                    :name="old('name', $filterByName)"
                    class="mb-6"
                />
                <div class="flex items-center gap-4 mb-4">
                    <flux:button variant="primary" href="{{ route('administratives.create') }}">Criar um novo administrativo</flux:button>
                </div>
                <div class="my-4 font-base text-sm text-gray-700 dark:text-gray-300">
                    <x-administratives.table :administratives="$administratives"
                                             :showView="true"
                                             :showEdit="true"
                                             :showDelete="true"
                    />
                </div>
                <div class="mt-4">
                    {{ $administratives->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts::main-content>
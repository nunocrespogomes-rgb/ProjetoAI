<x-layouts::main-content title="Administradores"
                         heading="Lista de Funcionários/Administradores"
                         subheading="Gerir os empregados da instituição">
    <div class="space-y-6 p-6 max-w-7xl mx-auto">

        <x-administratives.filter-card
            :filterAction="route('administratives.index')"
            :resetUrl="route('administratives.index')"
            :name="old('name', $filterByName)"
        />

        <div>
            <flux:button href="{{ route('administratives.create') }}" variant="filled" class="cursor-pointer bg-white text-zinc-900 border border-zinc-200 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 dark:hover:bg-zinc-700">
                {{ __('Criar um novo empregado') }}
            </flux:button>
        </div>

        <x-administratives.table
            :administratives="$administratives"
            :showView="true"
            :showEdit="true"
            :showDelete="true"
        />

        {{-- Paginação --}}
        @if(method_exists($administratives, 'links') && $administratives->hasPages())
            <div class="mt-4">
                {{ $administratives->links() }}
            </div>
        @endif
    </div>
</x-layouts::main-content>

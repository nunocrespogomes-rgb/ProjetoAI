<x-layouts::main-content
    title="Encomendas"
    heading="Gestão de Encomendas"
    subheading="Consulta e acompanhamento do fluxo de pedidos">

    <div class="p-6 space-y-6">

        @if(auth()->user()->isAdmin())
            <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700">
                <form action="{{ route('orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">Estado</label>
                        <select name="status" class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3">
                            <option value="">Todos os Estados</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente (Pending)</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fechada (Closed)</option>
                            <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Cancelada (Canceled)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">ID do Cliente</label>
                        <input type="number" name="customer_id" value="{{ request('customer_id') }}" placeholder="Ex: 5"
                               class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-500 uppercase mb-1">Data</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-sm px-3">
                    </div>

                    <div class="flex gap-2">
                        <flux:button type="submit" variant="primary" class="flex-1 h-10 justify-center" icon="magnifying-glass">
                            Filtrar
                        </flux:button>
                        @if(request()->anyFilled(['status', 'customer_id', 'date']))
                            <flux:button href="{{ route('orders.index') }}" variant="subtle" class="h-10" icon="x-mark">
                                Limpar
                            </flux:button>
                        @endif
                    </div>

                </form>
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white dark:bg-zinc-900 p-8 rounded-xl border border-zinc-200 dark:border-zinc-700 text-center">
                <p class="text-zinc-500 dark:text-zinc-400">Nenhuma encomenda encontrada.</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            <th class="p-4">ID</th>

                            @if(auth()->user()->isAdmin())
                                <th class="p-4">Cliente</th>
                            @endif

                            <th class="p-4">Data</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Método</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 text-sm text-zinc-800 dark:text-zinc-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/50 transition">
                                <td class="p-4 font-bold">#{{ $order->id }}</td>

                                @if(auth()->user()->isAdmin())
                                    <td class="p-4 text-xs text-zinc-500">
                                        ID: {{ $order->customer_id }} {{ $order->customer->user->name ?? '' }}
                                    </td>
                                @endif

                                <td class="p-4">{{ $order->date instanceof \Carbon\Carbon ? $order->date->format('Y-m-d') : substr($order->date, 0, 10) }}</td>
                                <td class="p-4 font-semibold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format($order->total_price, 2) }}€
                                </td>
                                <td class="p-4">{{ $order->payment_type }}</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                        @elseif($order->status === 'closed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                        @else bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <flux:button href="{{ route('orders.show', $order) }}" variant="subtle" size="sm" icon="eye" wire:navigate>
                                        Ver Detalhes
                                    </flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-layouts::main-content>
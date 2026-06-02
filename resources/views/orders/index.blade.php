<x-layouts::main-content
    title="As Minhas Encomendas"
    heading="Histórico de Encomendas"
    subheading="Consulta e acompanha o estado dos teus pedidos">

    <div class="p-6">
        @if($orders->isEmpty())
            <div class="bg-white dark:bg-zinc-900 p-8 rounded-xl border border-zinc-200 dark:border-zinc-700 text-center">
                <p class="text-zinc-500 dark:text-zinc-400">Ainda não efetuou nenhuma encomenda.</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            <th class="p-4">ID</th>
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
                                <td class="p-4">{{ $order->date }}</td>
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
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts::main-content>
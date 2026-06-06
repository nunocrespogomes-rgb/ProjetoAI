<x-layouts::main-content
    title="Detalhes da Encomenda #{{ $order->id }}"
    heading="Encomenda #{{ $order->id }}"
    subheading="Informação detalhada e histórico do pedido">

    <div class="p-6 space-y-6">
        
        {{-- Bloco de Estado da Encomenda --}}
        <div class="p-4 rounded-xl border flex flex-col gap-2 
            @if($order->status === 'pending') bg-amber-50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300
            @elseif($order->status === 'closed') bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300
            @else bg-rose-50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 @endif">
            
            <div class="flex items-center justify-between">
                <span class="font-bold text-sm uppercase tracking-wider">Estado Atual: {{ $order->status }}</span>
                
                @if($order->status === 'closed')
                    <flux:button href="{{ route('orders.receipt', $order) }}" icon="arrow-down-tray" variant="filled" size="sm">
                        Descarregar Recibo (PDF)
                    </flux:button>
                @endif
            </div>

            @if($order->status === 'canceled' && $order->reason_for_cancellation)
                <div class="mt-2 pt-2 border-t border-rose-200 dark:border-rose-800 text-sm">
                    <strong class="block font-semibold text-rose-900 dark:text-rose-400">Razão do Cancelamento:</strong>
                    <p class="italic text-rose-700 dark:text-rose-300">{{ $order->reason_for_cancellation }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Coluna Principal: Dados Praticados e Artigos --}}
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-2">Dados Praticados no Checkout</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-zinc-400 text-xs uppercase font-medium">NIF de Faturação</span>
                        <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $order->nif }}</span>
                    </div>
                    <div>
                        <span class="block text-zinc-400 text-xs uppercase font-medium">Método de Pagamento</span>
                        <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $order->payment_type }} ({{ $order->payment_ref }})</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-zinc-400 text-xs uppercase font-medium">Endereço de Entrega</span>
                        <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $order->address }}</span>
                    </div>
                    @if($order->notes)
                        <div class="md:col-span-2">
                            <span class="block text-zinc-400 text-xs uppercase font-medium">Notas Adicionais</span>
                            <p class="text-zinc-600 dark:text-zinc-400 italic bg-zinc-50 dark:bg-zinc-800/50 p-2 rounded mt-1">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>

                <h3 class="text-lg font-bold text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pt-4 pb-2">Artigos Adquiridos</h3>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($order->items as $item)
                        <div class="py-4 flex justify-between items-center text-sm gap-4">
                            
                            <div class="flex items-center gap-4">
                                {{-- Miniatura da T-Shirt --}}
                                <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden flex-shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    @if($item->tshirtImage && $item->tshirtImage->image_url)
                                        <img src="{{ asset('storage/tshirt_images/' . $item->tshirtImage->image_url) }}" 
                                             alt="T-shirt" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/photos/anonymous.png') }}" 
                                             alt="Sem imagem" 
                                             class="w-full h-full object-cover opacity-40">
                                    @endif
                                </div>

                                {{-- Dados detalhados do Artigo --}}
                                <div>
                                    <p class="font-bold text-zinc-800 dark:text-zinc-200">T-Shirt ID: {{ $item->tshirt_image_id }}</p>
                                    
                                    {{-- Exibição do Nome Real da Cor --}}
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        Tamanho: {{ $item->size }} | 
                                        Cor: <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $item->color->name ?? 'N/A' }}</span> ({{ $item->color_code }})
                                    </p>
                                    
                                    <p class="text-xs text-zinc-400">
                                        Qtd: {{ $item->qty }} x {{ number_format($item->unit_price, 2) }}€

                                        {{-- Lógica Dinâmica de Cálculo de Desconto por Quantidade --}}
                                        @php
                                            $priceRecord = \App\Models\Price::find(1); 
                                            $isCatalog = is_null($item->tshirtImage->customer_id ?? null);
                                            $basePrice = $isCatalog ? ($priceRecord->unit_price_catalog ?? 0) : ($priceRecord->unit_price_own ?? 0);
                                            
                                            $discountPerUnit = $basePrice - $item->unit_price;
                                        @endphp

                                        @if($discountPerUnit > 0.01)
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
                                                Desconto: {{ number_format($discountPerUnit * $item->qty, 2) }}€ (Poupou {{ number_format(($discountPerUnit / $basePrice) * 100, 0) }}%)
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <span class="font-bold text-zinc-900 dark:text-white">{{ number_format($item->sub_total, 2) }}€</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Coluna Lateral: Ações Administrativas para Funcionários e Admins --}}
            @if(auth()->user() && (strtoupper(trim(auth()->user()->user_type)) === 'F' || strtoupper(trim(auth()->user()->user_type)) === 'A'))
                <div class="lg:col-span-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 space-y-4 h-fit">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white border-b border-zinc-100 dark:border-zinc-800 pb-2">Ações Administrativas</h3>
                    
                    @if($order->status === 'pending')
                        <form action="{{ route('orders.close', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <flux:button type="submit" variant="primary" class="w-full justify-center" icon="check-circle">
                                Declarar como Fechada (Closed)
                            </flux:button>
                        </form>

                        @if(strtoupper(trim(auth()->user()->user_type)) === 'A')
                            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                                <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Anular Encomenda</h4>
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs text-zinc-500 mb-1">Razão da Anulação (Opcional):</label>
                                        <input type="text" name="reason_for_cancellation" placeholder="Ex: Rutura de stock no fornecedor"
                                               class="w-full h-10 rounded-lg border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white text-xs px-3 py-1">
                                    </div>
                                    <flux:button type="submit" variant="danger" class="w-full justify-center" icon="x-circle">
                                        Anular Encomenda (Canceled)
                                    </flux:button>
                                </form>
                            </div>
                        @endif
                    @else
                        <p class="text-xs text-zinc-400 italic text-center py-4">Esta encomenda já se encontra processada e encerrada.</p>
                    @endif
                </div>
            @endif
        </div>

    </div>
</x-layouts::main-content>
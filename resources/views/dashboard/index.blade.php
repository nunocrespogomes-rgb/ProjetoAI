<x-layouts::main-content
    title="Dashboard Estatístico"
    heading="Painel de Desempenho"
    subheading="Análise de métricas de negócio e fluxo de vendas da FunShirt">

    <div class="p-6 space-y-6">
        
        {{-- Linha de KPIs (Cartões de Métricas Rápidas) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Cartão 1: Volume Total de Vendas --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 flex items-center justify-between shadow-sm">
                <div>
                    <span class="block text-zinc-400 text-xs uppercase font-semibold tracking-wider">Volume Total de Vendas</span>
                    <h3 class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">
                        {{ number_format($totalVendas, 2) }}€
                    </h3>
                </div>
                <div class="p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Cartão 2: Produtos Vendidos --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 flex items-center justify-between shadow-sm">
                <div>
                    <span class="block text-zinc-400 text-xs uppercase font-semibold tracking-wider">T-Shirts Processadas</span>
                    <h3 class="text-3xl font-extrabold text-zinc-800 dark:text-white mt-1">
                        {{ $totalTshirts }} unidades
                    </h3>
                </div>
                <div class="p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

        </div>

        {{-- Secção de Distribuição de Dados --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Bloco 1: Evolução Mensal (Tabela Estruturada) --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6">
                <h4 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Evolução de Faturação Mensal</h4>
                
                <div class="overflow-hidden border border-zinc-100 dark:border-zinc-800 rounded-lg">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-800 text-xs font-bold uppercase text-zinc-500 tracking-wider">
                                <th class="p-3">Período Temporal</th>
                                <th class="p-3 text-right">Total Faturado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-sm text-zinc-700 dark:text-zinc-300">
                            @foreach($vendasMensais as $venda)
                                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                                    <td class="p-3 font-medium">Mês {{ $venda->mes }}</td>
                                    <td class="p-3 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($venda->total, 2) }}€
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Bloco 2: Ranking Top 5 Estampas (Com Barras Visuais de Proporção em CSS) --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6">
                <h4 class="text-base font-bold text-zinc-900 dark:text-white mb-4">Top 5 Estampas Mais Vendidas (Volume de Qtd)</h4>
                
                <div class="space-y-4">
                    @php
                        // Encontrar a quantidade máxima vendida do primeiro lugar para servir de teto (100%) à barra CSS
                        $maxQty = $topImagens->first()->total_qty ?? 1;
                    @endphp

                    @foreach($topImagens as $index => $imagem)
                        @php
                            // Calcular a percentagem visual que esta estampa representa face ao primeiro lugar
                            $percentage = ($imagem->total_qty / $maxQty) * 100;
                        @endphp
                        
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-zinc-700 dark:text-zinc-300 flex items-center gap-2">
                                    <span class="text-xs font-bold text-zinc-400">#{{ $index + 1 }}</span> 
                                    T-Shirt Imagem ID: {{ $imagem->tshirt_image_id }}
                                </span>
                                <span class="font-bold text-zinc-900 dark:text-white">
                                    {{ $imagem->total_qty }} <span class="text-xs font-normal text-zinc-400">unids</span>
                                </span>
                            </div>
                            
                            {{-- Tráfego/Barra de progresso puramente em CSS via Tailwind --}}
                            <div class="w-full bg-zinc-100 dark:bg-zinc-800 rounded-full h-3 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full transition-all duration-500" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</x-layouts::main-content>
<x-layouts::main-content title="Dashboard Estatístico" heading="Painel de Desempenho" subheading="Métricas de negócio · FunShirt · {{ now()->format('d/m/Y H:i') }}">
    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Volume Total</p>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 truncate mt-0.5">{{ number_format($totalVendas, 2) }}€</p>
                    <p class="text-xs text-zinc-400 mt-0.5">Receita acumulada</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Encomendas</p>
                    <p class="text-2xl font-extrabold text-zinc-800 dark:text-white mt-0.5">{{ number_format($totalPedidos) }}</p>
                    <p class="text-xs text-zinc-400 mt-0.5">Pedidos concluídos</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                <div class="p-3 rounded-xl bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">Ticket Médio</p>
                    <p class="text-2xl font-extrabold text-violet-600 dark:text-violet-400 truncate mt-0.5">{{ number_format($mediaEncomenda, 2) }}€</p>
                    <p class="text-xs text-zinc-400 mt-0.5">Por encomenda</p>
                </div>
            </div>
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5 flex items-center gap-4 shadow-sm">
                <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-400">T-Shirts Vendidas</p>
                    <p class="text-2xl font-extrabold text-zinc-800 dark:text-white mt-0.5">{{ number_format($totalTshirts) }}</p>
                    <p class="text-xs text-zinc-400 mt-0.5">Unidades processadas</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-500 flex items-center gap-1.5 mb-3">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    Melhor Mês
                </p>
                @if($mesMaisVendas)
                <p class="text-xl font-extrabold text-emerald-800 dark:text-emerald-200">{{ $mesMaisVendas->nome_mes }}</p>
                <p class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($mesMaisVendas->total, 2) }}€</p>
                <p class="text-xs text-emerald-500 mt-1">{{ $mesMaisVendas->num_pedidos }} encomendas</p>
                @else
                <p class="text-sm text-emerald-500 mt-2">Sem dados</p>
                @endif
            </div>
            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800/50 rounded-xl p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-red-500 dark:text-red-400 flex items-center gap-1.5 mb-3">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    Mês Mais Fraco
                </p>
                @if($mesMenosVendas)
                <p class="text-xl font-extrabold text-red-800 dark:text-red-200">{{ $mesMenosVendas->nome_mes }}</p>
                <p class="text-base font-bold text-red-500 dark:text-red-400 mt-1">{{ number_format($mesMenosVendas->total, 2) }}€</p>
                <p class="text-xs text-red-400 mt-1">{{ $mesMenosVendas->num_pedidos }} encomendas</p>
                @else
                <p class="text-sm text-red-400 mt-2">Sem dados</p>
                @endif
            </div>
            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 flex items-center gap-1.5 mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16" /></svg>
                    Média Mensal
                </p>
                <p class="text-xl font-extrabold text-blue-800 dark:text-blue-200">{{ number_format($mediaVendasMensais, 2) }}€</p>
                <p class="text-xs text-blue-500 mt-2">Receita média por mês ativo</p>
            </div>
            @php
            $isNeg = $crescimentoAnual < 0; $isNull = $crescimentoAnual === null;
            $yoyBg = $isNull ? 'bg-zinc-50 dark:bg-zinc-800/50 border-zinc-200 dark:border-zinc-700' : ($isNeg ? 'bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/50' : 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/50');
            $yoyLabel = $isNull ? 'text-zinc-500 dark:text-zinc-400' : ($isNeg ? 'text-red-500 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400');
            $yoyVal = $isNull ? 'text-zinc-600 dark:text-zinc-300' : ($isNeg ? 'text-red-700 dark:text-red-300' : 'text-emerald-700 dark:text-emerald-300');
            @endphp
            <div class="{{ $yoyBg }} border rounded-xl p-5">
                <p class="text-xs font-bold uppercase tracking-wider {{ $yoyLabel }} flex items-center gap-1.5 mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Crescimento {{ $anoAtual }}
                </p>
                @if(!$isNull)
                <p class="text-xl font-extrabold {{ $yoyVal }}">{{ $crescimentoAnual >= 0 ? '+' : '' }}{{ number_format($crescimentoAnual, 1) }}%</p>
                <p class="text-xs text-zinc-400 mt-2 leading-relaxed">{{ $anoAtual - 1 }}: {{ number_format($vendasAnoAnterior, 2) }}€<br>{{ $anoAtual }}: {{ number_format($vendasAnoAtual, 2) }}€</p>
                @else
                <p class="text-xl font-extrabold text-zinc-400">—</p>
                <p class="text-xs text-zinc-400 mt-2">Sem dados do ano anterior</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h4 class="text-base font-bold text-zinc-900 dark:text-white">Evolução Mensal Detalhada (Ano 2025)</h4>
                    <span class="text-xs text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-1 rounded-full font-medium">{{ $vendasMensais->count() }} meses</span>
                </div>
                @if($vendasMensais->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[500px]">
                        <thead>
                            <tr class="border-b border-zinc-100 dark:border-zinc-800 text-left text-xs font-bold uppercase tracking-wider text-zinc-400">
                                <th class="pb-3 px-2">Mês</th><th class="pb-3 px-2 text-right">Pedidos</th><th class="pb-3 px-2 text-right">Total</th><th class="pb-3 px-2 text-right">Média</th><th class="pb-3 px-2 text-right text-blue-400">Máx.</th><th class="pb-3 px-2 text-right text-red-400">Mín.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50 dark:divide-zinc-800/80">
                            @foreach($vendasMensais as $v)
                            @php
                            $isBest = $mesMaisVendas && $v->mes === $mesMaisVendas->mes; $isWorst = $mesMenosVendas && $v->mes === $mesMenosVendas->mes;
                            @endphp
                            <tr class="transition @if($isBest) bg-emerald-50/70 dark:bg-emerald-950/10 @elseif($isWorst) bg-red-50/50 dark:bg-red-950/10 @else hover:bg-zinc-50/70 dark:hover:bg-zinc-800/20 @endif">
                                <td class="py-2.5 px-2">
                                    <span class="font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                                        <span class="w-1.5 h-4 rounded-full shrink-0 @if($isBest) bg-emerald-400 @elseif($isWorst) bg-red-400 @else bg-zinc-200 dark:bg-zinc-700 @endif"></span>
                                        {{ $v->nome_mes }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-2 text-right text-zinc-500 dark:text-zinc-400">{{ $v->num_pedidos }}</td>
                                <td class="py-2.5 px-2 text-right font-bold @if($isBest) text-emerald-600 dark:text-emerald-400 @elseif($isWorst) text-red-500 dark:text-red-400 @else text-zinc-800 dark:text-zinc-200 @endif">{{ number_format($v->total, 2) }}€</td>
                                <td class="py-2.5 px-2 text-right text-zinc-500 dark:text-zinc-400">{{ number_format($v->media, 2) }}€</td>
                                <td class="py-2.5 px-2 text-right text-blue-500 dark:text-blue-400">{{ number_format($v->maximo, 2) }}€</td>
                                <td class="py-2.5 px-2 text-right text-red-400">{{ number_format($v->minimo, 2) }}€</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-zinc-200 dark:border-zinc-700 font-bold text-sm">
                                <td class="pt-3 px-2"><span class="text-zinc-700 dark:text-zinc-300 flex items-center gap-2"><span class="w-1.5 h-4 shrink-0"></span>Total</span></td>
                                <td class="pt-3 px-2 text-right text-zinc-600 dark:text-zinc-400">{{ $totalPedidos }}</td>
                                <td class="pt-3 px-2 text-right text-emerald-600 dark:text-emerald-400">{{ number_format($totalVendas, 2) }}€</td>
                                <td class="pt-3 px-2 text-right text-zinc-500">{{ number_format($mediaEncomenda, 2) }}€</td>
                                <td class="pt-3 px-2" colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-sm text-zinc-400 py-8 text-center">Nenhum dado de vendas disponível.</p>
                @endif
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm">
                <h4 class="text-base font-bold text-zinc-900 dark:text-white mb-5">Top 5 Categorias</h4>
                @php
                $totalCatQty = $vendasPorCategoria->sum('total_qty') ?: 1; $maxCatQty = $vendasPorCategoria->first()->total_qty ?? 1;
                $palette = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
                $badgeCls = ['bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300', 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300', 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300', 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300', 'bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300'];
                @endphp
                <div class="space-y-5">
                    @forelse($vendasPorCategoria as $i => $cat)
                    @php
                    $pctBar = round(($cat->total_qty / $maxCatQty) * 100, 1); $pctShare = round(($cat->total_qty / $totalCatQty) * 100, 1);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full {{ $badgeCls[$i] }} text-xs font-bold flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                                {{ $cat->categoria }}
                            </span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">
                                {{ number_format($cat->total_qty) }}<span class="text-xs font-normal text-zinc-400 ml-0.5">un.</span>
                                <span class="text-xs font-medium text-zinc-400 ml-1">({{ $pctShare }}%)</span>
                            </span>
                        </div>
                        <div class="w-full bg-zinc-100 dark:bg-zinc-800 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: {{ $pctBar }}%; background-color: {{ $palette[$i] }}"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-zinc-400 py-6 text-center">Sem dados de categorias disponíveis.</p>
                    @endforelse
                </div>
                @if($vendasPorCategoria->isNotEmpty())
                <div class="mt-5 pt-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-between text-xs text-zinc-400 font-medium">
                    <span>{{ $vendasPorCategoria->count() }} categorias</span>
                    <span>Total: {{ number_format($vendasPorCategoria->sum('total_qty')) }} unidades</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::main-content>
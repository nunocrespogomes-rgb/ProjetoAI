<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    private array $nomesMeses = [
        '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março',
        '04' => 'Abril', '05' => 'Maio', '06' => 'Junho',
        '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro',
        '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro',
    ];

    public function index()
    {
        $totalVendas = Order::where('status', 'closed')->sum('total_price');
        $totalPedidos = Order::where('status', 'closed')->count();
        $mediaEncomenda = $totalPedidos > 0 ? $totalVendas / $totalPedidos : 0;
        $totalTshirts = OrderItem::whereHas('order', fn($q) => $q->where('status', 'closed'))->sum('qty');

        $vendasMensais = Order::where('status', 'closed')
            ->whereRaw("strftime('%Y', date) = '2025'")
            ->select(
                DB::raw("strftime('%m', date) as mes"),
                DB::raw("COUNT(*) as num_pedidos"),
                DB::raw("SUM(total_price) as total"),
                DB::raw("AVG(total_price) as media"),
                DB::raw("MAX(total_price) as maximo"),
                DB::raw("MIN(total_price) as minimo"),
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($v) {
                $v->nome_mes = $this->nomesMeses[$v->mes] ?? "Mês {$v->mes}";
                $v->total = (float) $v->total;
                $v->media = (float) $v->media;
                $v->maximo = (float) $v->maximo;
                $v->minimo = (float) $v->minimo;
                return $v;
            });

        $mesMaisVendas = $vendasMensais->isNotEmpty() ? $vendasMensais->sortByDesc('total')->first() : null;
        $mesMenosVendas = $vendasMensais->isNotEmpty() ? $vendasMensais->sortBy('total')->first() : null;
        $mediaVendasMensais = $vendasMensais->isNotEmpty() ? $vendasMensais->avg('total') : 0;

        $anoAtual = (int) date('Y');
        $vendasAnoAtual = Order::where('status', 'closed')
            ->whereRaw("strftime('%Y', date) = ?", [(string) $anoAtual])
            ->sum('total_price');
        $vendasAnoAnterior = Order::where('status', 'closed')
            ->whereRaw("strftime('%Y', date) = ?", [(string) ($anoAtual - 1)])
            ->sum('total_price');
        $crescimentoAnual = $vendasAnoAnterior > 0
            ? (($vendasAnoAtual - $vendasAnoAnterior) / $vendasAnoAnterior) * 100
            : null;

        $vendasPorCategoria = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->join('categories', 'tshirt_images.category_id', '=', 'categories.id')
            ->where('orders.status', 'closed')
            ->select(
                'categories.name as categoria',
                DB::raw('SUM(order_items.qty) as total_qty'),
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalVendas', 'totalPedidos', 'mediaEncomenda', 'totalTshirts',
            'vendasMensais', 'mesMaisVendas', 'mesMenosVendas', 'mediaVendasMensais',
            'vendasAnoAtual', 'vendasAnoAnterior', 'crescimentoAnual', 'anoAtual',
            'vendasPorCategoria'
        ));
    }
}
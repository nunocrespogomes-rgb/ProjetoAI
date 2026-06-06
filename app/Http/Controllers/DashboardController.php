<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Volume Total de Vendas (€) - Apenas encomendas concluídas
        $totalVendas = Order::where('status', 'closed')->sum('total_price');

        // 2. Quantidade Total de T-shirts Vendidas
        $totalTshirts = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'closed');
        })->sum('qty');

        // 3. Faturação Mensal (Agrupado por Mês para o Gráfico de Linha)
        // Nota: strftime('%m', date) é para SQLite. Se usares MySQL, mudas para DB::raw("MONTH(date)")
        $vendasMensais = Order::where('status', 'closed')
            ->select(
                DB::raw("strftime('%m', date) as mes"),
                DB::raw("SUM(total_price) as total")
            )
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // 4. Top 5 Estampas/Imagens mais vendidas (Para o Gráfico de Barras)
        $topImagens = OrderItem::whereHas('order', function ($query) {
                $query->where('status', 'closed');
            })
            ->select('tshirt_image_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('tshirt_image_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        // Retorna a view 'dashboard' que criámos no passo anterior com os dados engatados
        return view('dashboard.index', compact('totalVendas', 'totalTshirts', 'vendasMensais', 'topImagens'));
    }
}
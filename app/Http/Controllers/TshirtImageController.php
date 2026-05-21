<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TshirtImageController extends Controller
{
    
    //   Exibe o catálogo público de t-shirts com filtros e paginação.
     
    public function index(Request $request): View
    {
        // 1. Iniciamos a query trazendo apenas os designs públicos (customer_id é NULL)
        // Usamos o with('category') para otimizar o carregamento da base de dados (Eager Loading)
        $query = TshirtImage::with('category')->whereNull('customer_id');

        // 2. Aplicar Filtro por Categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 3. Aplicar Filtro por Texto (Nome ou Descrição)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 4. Paginação de 12 em 12 itens (ideal para uma grelha de 3 ou 4 colunas)
        // O appends() garante que os filtros não se perdem ao mudar de página
        $tshirtImages = $query->orderBy('name')->paginate(12)->withQueryString();

        // 5. Procurar todas as categorias para preencher o transbordo (select) do filtro
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('tshirtImages', 'categories'));
    }

    /**
     * Exibe o detalhe de uma t-shirt específica (G7 - Preview e seleção)
     */
    public function show(TshirtImage $tshirtImage): View
    {
        // Se alguém tentar aceder a uma imagem privada através do ID na URL pública, barramos
        if ($tshirtImage->customer_id !== null) {
            abort(403, 'Esta imagem é privada.');
        }

        return view('catalog.show', compact('tshirtImage'));
    }
}
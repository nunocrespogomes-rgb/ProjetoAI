<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTshirtRequest;
use App\Http\Requests\UpdateTshirtRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminTshirtController extends Controller
{
    public function index(Request $request): View
    {

        $this->authorize('viewAny', TshirtImage::class);

        $tshirts = TshirtImage::with('category')
            ->whereNull('customer_id') // só catálogo, não imagens privadas de clientes
            ->when($request->filled('search'), fn ($q) =>
            $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();



        return view('admin.tshirts.index', compact('tshirts'));
    }

    public function create(): View
    {
        $this->authorize('create', TshirtImage::class);

        $categories = Category::orderBy('name')->get();

        return view('admin.tshirts.create', compact('categories'));
    }

    public function store(StoreTshirtRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $path = $request->file('image_file')->store('tshirt_images', 'public');

        $tshirt = new TshirtImage();
        $tshirt->name        = $validated['name'];
        $tshirt->description = $validated['description'] ?? null;
        $tshirt->category_id = $validated['category_id'];
        $tshirt->customer_id = null;
        $tshirt->image_url   = basename($path);
        $tshirt->save();

        return redirect()
            ->route('admin.tshirts.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'T-shirt adicionada ao catálogo com sucesso.');
    }

    public function edit(TshirtImage $tshirt): View
    {
        $this->authorize('update', $tshirt);

        $categories = Category::orderBy('name')->get();

        // Descobrir extensões disponíveis para cada cor
        $colors = Color::orderBy('name')->get();
        $colorExtensions = [];
        foreach ($colors as $color) {
            foreach (['jpg', 'png', 'jpeg', 'webp'] as $ext) {
                if (Storage::disk('public')->exists('tshirt_base/' . $color->code . '.' . $ext)) {
                    $colorExtensions[$color->code] = $ext;
                    break;
                }
            }
        }

        return view('admin.tshirts.edit', compact('tshirt', 'categories', 'colorExtensions'));
    }

    public function update(UpdateTshirtRequest $request, TshirtImage $tshirt): RedirectResponse
    {
        // authorize() tratado no UpdateTshirtRequest
        $validated = $request->validated();

        $tshirt->name        = $validated['name'];
        $tshirt->description = $validated['description'] ?? null;
        $tshirt->category_id = $validated['category_id'];

        if ($request->hasFile('image_file')) {
            if ($tshirt->image_url) {
                Storage::disk('public')->delete('tshirt_images/' . $tshirt->image_url);
            }
            $path = $request->file('image_file')->store('tshirt_images', 'public');
            $tshirt->image_url = basename($path);
        }

        $tshirt->save();

        return redirect()
            ->route('admin.tshirts.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'T-shirt atualizada com sucesso.');
    }

    public function destroy(TshirtImage $tshirt): RedirectResponse
    {
        $this->authorize('delete', $tshirt);

        if ($tshirt->image_url) {
            Storage::disk('public')->delete('tshirt_images/' . $tshirt->image_url);
        }

        $tshirt->delete();

        return redirect()
            ->route('admin.tshirts.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'T-shirt removida do catálogo com sucesso.');
    }
}

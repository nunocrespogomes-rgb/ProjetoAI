<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && strtoupper(trim(Auth::user()->user_type)) === 'A', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin();

        $categories = Category::query()
            ->when($request->filled('name'), fn ($query) =>
                $query->where('name', 'like', '%' . $request->name . '%')
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        return view('admin.categories.create', ['category' => new Category()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'image_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->image_url = null;
        $category->save();

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $category->image_url = basename($path);
            $category->save();
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Categoria criada com sucesso.');
    }

    public function edit(Category $category): View
    {
        $this->authorizeAdmin();

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'image_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $category->name = $validated['name'];

        if ($request->hasFile('image_file')) {
            if ($category->image_url) {
                Storage::disk('public')->delete('categories/' . $category->image_url);
            }

            $path = $request->file('image_file')->store('categories', 'public');
            $category->image_url = basename($path);
        }

        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($category->tshirtImages()->exists()) {
            return back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'Não é possível apagar esta categoria porque existem imagens associadas.');
        }

        if ($category->image_url) {
            Storage::disk('public')->delete('categories/' . $category->image_url);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Categoria removida com sucesso.');
    }
}

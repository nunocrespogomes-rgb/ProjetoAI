<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Category::class);

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
        $this->authorize('create', Category::class);

        return view('admin.categories.create', ['category' => new Category()]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        // authorize() tratado no StoreCategoryRequest
        $validated = $request->validated();



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
        $this->authorize('update', $category);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        // authorize() tratado no UpdateCategoryRequest
        $validated = $request->validated();

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
        $this->authorize('delete', $category);

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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminColorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Color::class);

        $colors = Color::query()
            ->when($request->filled('search'), fn ($query) =>
            $query->where('code', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.colors.index', compact('colors'));
    }

    public function create(): View
    {
        $this->authorize('create', Color::class);

        return view('admin.colors.create', ['color' => new Color()]);
    }

    public function store(StoreColorRequest $request): RedirectResponse
    {
        // authorize() tratado no StoreColorRequest
        $validated = $request->validated();

        $color = new Color();
        $color->code = $validated['code'];
        $color->name = $validated['name'];
        $color->save();

        $this->storeBaseImageIfPresent($request, $color->code);

        return redirect()
            ->route('admin.colors.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Cor criada com sucesso.');
    }

    public function edit(Color $color): View
    {
        $this->authorize('update', $color);

        return view('admin.colors.edit', compact('color'));
    }

    public function update(UpdateColorRequest $request, Color $color): RedirectResponse
    {
        // authorize() tratado no UpdateColorRequest
        $validated = $request->validated();

        $color->name = $validated['name'];
        $color->save();

        $this->storeBaseImageIfPresent($request, $color->code);

        return redirect()
            ->route('admin.colors.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Cor atualizada com sucesso.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $this->authorize('delete', $color);

        if ($color->orderItems()->exists()) {
            return back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', 'Não é possível apagar esta cor porque já existem encomendas associadas.');
        }

        $this->deleteKnownBaseImages($color->code);
        $color->delete();

        return redirect()
            ->route('admin.colors.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Cor removida com sucesso.');
    }

    private function storeBaseImageIfPresent(Request $request, string $code): void
    {
        if (!$request->hasFile('base_image')) {
            return;
        }

        $this->deleteKnownBaseImages($code);

        $extension = $request->file('base_image')->extension();
        $fileName = $code . '.' . $extension;

        Storage::disk('public')->putFileAs(
            'tshirt_base',
            $request->file('base_image'),
            $fileName
        );
    }

    private function deleteKnownBaseImages(string $code): void
    {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $extension) {
            Storage::disk('public')->delete('tshirt_base/' . $code . '.' . $extension);
        }
    }
}

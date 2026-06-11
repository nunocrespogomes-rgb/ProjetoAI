<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminColorController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && strtoupper(trim(Auth::user()->user_type)) === 'A', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

        return view('admin.colors.create', ['color' => new Color()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:colors,code'],
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'max:4096'],
        ]);

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
        $this->authorizeAdmin();

        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'max:4096'],
        ]);

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
        $this->authorizeAdmin();

        if (method_exists($color, 'orderItems') && $color->orderItems()->exists()) {
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

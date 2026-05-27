<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomTshirtImageController extends Controller
{
    private function customerId(): int
    {
        return Auth::user()->customer->firstOrFail()->id;
    }

    private function authorizeOwner(TshirtImage $tshirtImage): void
    {
        abort_if($tshirtImage->customer_id !== $this->customerId(), 403);
    }

    public function index(): View
    {
        $tshirtImages = TshirtImage::where('customer_id', $this->customerId())
            ->whereNull('category_id')
            ->latest()
            ->paginate(12);

        return view('customer/my_images.index', compact('tshirtImages'));
    }

    public function create(): View
    {
        return view('customer/my_images.create');
    }

    public function store(Request $request): RedirectResponse
    {
//        MUDAR PARA REQUEST!!
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $path = $request->file('image')->store('private/tshirt_images_private');

        TshirtImage::create([
            'customer_id' => $this->customerId(),
            'category_id' => null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image_url' => basename($path),
        ]);

        return redirect()
            ->route('customer.tshirt-images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada adicionada com sucesso.');
    }

    public function show(TshirtImage $tshirtImage): View
    {
        $this->authorizeOwner($tshirtImage);

        $colors = Color::orderBy('name')->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        $price = Price::first();

        return view('customer.tshirt-images.show', compact(
            'tshirtImage',
            'colors',
            'sizes',
            'price'
        ));
    }

    public function edit(TshirtImage $tshirtImage): View
    {
        $this->authorizeOwner($tshirtImage);

        return view('customer.tshirt-images.edit', compact('tshirtImage'));
    }

    public function update(Request $request, TshirtImage $tshirtImage): RedirectResponse
    {
        $this->authorizeOwner($tshirtImage);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => null,
        ];

        if ($request->hasFile('image')) {
            if ($tshirtImage->image_url) {
                Storage::delete('private/my_images_private/' . $tshirtImage->image_url);
            }

            $path = $request->file('image')->store('private/my_images_private');
            $data['image_url'] = basename($path);
        }

        $tshirtImage->update($data);

        return redirect()
            ->route('customer.tshirt-images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada atualizada com sucesso.');
    }

    public function destroy(TshirtImage $tshirtImage): RedirectResponse
    {
        $this->authorizeOwner($tshirtImage);

        if ($tshirtImage->image_url) {
            Storage::delete('private/my_images_private/' . $tshirtImage->image_url);
        }

        $tshirtImage->delete();

        return redirect()
            ->route('customer.tshirt-images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada removida com sucesso.');
    }

    public function file(TshirtImage $tshirtImage): BinaryFileResponse
    {
        $this->authorizeOwner($tshirtImage);

        $path = storage_path('app/private/my_images_private/' . $tshirtImage->image_url);

        abort_unless(file_exists($path), 404);

        return response()->file($path);
    }
}

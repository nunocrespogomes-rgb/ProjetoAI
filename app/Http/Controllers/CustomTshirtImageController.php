<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTshirtImageRequest;
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

    private function authorizeOwner(TshirtImage $my_images): void
    {
        abort_if($my_images->customer_id !== $this->customerId(), 403);
    }

    public function index(): View
    {
        $my_images = TshirtImage::where('customer_id', $this->customerId())
            ->whereNull('category_id')
            ->latest()
            ->paginate(12);

        return view('customer.my_images.index', compact('my_images'));
    }

    public function create(): View
    {
        return view('customer/my_images.create');
    }

    public function store(StoreTshirtImageRequest $request): RedirectResponse
    {
//        MUDAR PARA REQUEST!!
        $validated = $request->validated();

        $path = $request->file('image')->store('my_images_private');

        TshirtImage::create([
            'customer_id' => $this->customerId(),
            'category_id' => null,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image_url' => basename($path),
        ]);

        return redirect()
            ->route('my_images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada adicionada com sucesso.');
    }

    public function show(TshirtImage $my_image): View
    {
        $this->authorizeOwner($my_image);

        $colors = Color::orderBy('name')->get();
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        $price = Price::first();

        return view('customer.my_images.show', compact(
            'my_image',
            'colors',
            'sizes',
            'price'
        ));
    }

    public function edit(TshirtImage $my_image): View
    {
        $this->authorizeOwner($my_image);

        return view('customer.my_images.edit', compact('my_image'));
    }

    public function update(Request $request, TshirtImage $my_image): RedirectResponse
    {
        $this->authorizeOwner($my_image);

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
            if ($my_image->image_url) {
                Storage::delete('my_images_private/' . $my_image->image_url);
            }

            $path = $request->file('image')->store('my_images_private');
            $data['image_url'] = basename($path);
        }

        $my_image->update($data);

        return redirect()
            ->route('my_images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada atualizada com sucesso.');
    }

    public function destroy(TshirtImage $my_image): RedirectResponse
    {
        $this->authorizeOwner($my_image);

        if ($my_image->image_url) {
            Storage::delete('my_images_private/' . $my_image->image_url);
        }

        $my_image->delete();

        return redirect()
            ->route('my_images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada removida com sucesso.');
    }

//    public function file(TshirtImage $my_image): BinaryFileResponse
//    {
//        $this->authorizeOwner($my_image);
//
//        $path = storage_path('app/private/my_images_private/' . $my_image->image_url);
//
//        abort_unless(file_exists($path), 404);
//
//        return response()->file($path);
//    }

    public function file(TshirtImage $my_image): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // SE A IMAGEM FOR PRIVADA (tem dono), valida a segurança normalmente
        if ($my_image->customer_id !== null) {
            $this->authorizeOwner($my_image);

            // Caminho da pasta privada
            $path = \Illuminate\Support\Facades\Storage::disk('local')
                ->path('my_images_private/' . $my_image->image_url);
        } else {
            // CASO CONTRÁRIO: Se por acaso uma imagem pública chamar este método,
            // ele vai buscar à pasta pública do catálogo sem dar erro 403
            $path = storage_path('app/public/tshirt_images/' . $my_image->image_url);
        }

        // Se o ficheiro físico não existir no disco, dá 404
        abort_unless(file_exists($path), 404);

        return response()->file($path);
    }


}

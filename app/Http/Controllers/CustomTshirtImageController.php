<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTshirtImageRequest;
use App\Http\Requests\UpdateTshirtImageRequest;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomTshirtImageController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TshirtImage::class);

        $my_images = TshirtImage::where('customer_id', auth()->id())
            ->whereNull('category_id')
            ->latest()
            ->paginate(12);

        return view('customer.my_images.index', compact('my_images'));
    }

    public function create(): View
    {
        $this->authorize('create', TshirtImage::class);

        return view('customer/my_images.create');
    }

    public function store(StoreTshirtImageRequest $request): RedirectResponse
    {
        $this->authorize('create', TshirtImage::class);

        $validated = $request->validated();

        $path = $request->file('image')->store('my_images_private');

        TshirtImage::create([
            'customer_id' => auth()->id(),
            'category_id' => null,
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image_url'   => basename($path),
        ]);

        return redirect()
            ->route('my_images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada adicionada com sucesso.');
    }

    public function show(TshirtImage $my_image): View
    {
        $this->authorize('view', $my_image);

        $colors = Color::orderBy('name')->get();
        $sizes  = ['XS', 'S', 'M', 'L', 'XL'];
        $price  = Price::first();

        return view('customer.my_images.show', compact('my_image', 'colors', 'sizes', 'price'));
    }

    public function edit(TshirtImage $my_image): View
    {
        $this->authorize('update', $my_image);

        return view('customer.my_images.edit', compact('my_image'));
    }

    public function update(UpdateTshirtImageRequest $request, TshirtImage $my_image): RedirectResponse
    {
        $this->authorize('update', $my_image);

        $validated = $request->validated();

        $data = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => null,
        ];

        if ($request->hasFile('image')) {
            if ($my_image->image_url) {
                Storage::delete('my_images_private/' . $my_image->image_url);
            }

            $path          = $request->file('image')->store('my_images_private');
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
        $this->authorize('delete', $my_image);

        if ($my_image->image_url) {
            Storage::delete('my_images_private/' . $my_image->image_url);
        }

        $my_image->delete();

        return redirect()
            ->route('my_images.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Imagem personalizada removida com sucesso.');
    }

    public function file(TshirtImage $my_image): BinaryFileResponse
    {
        $this->authorize('view', $my_image);

        abort_if(!$my_image->image_url, 404);

        $path = Storage::disk('local')->path('my_images_private/' . $my_image->image_url);

        abort_unless(file_exists($path), 404);

        return response()->file($path);
    }
}

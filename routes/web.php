<?php

use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\CustomTshirtImageController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Rota para a página inicial (aponta o catálogo como a homepage)
Route::get('/', [TshirtImageController::class, 'index'])->name('home');

// Grupo de Rotas do Catálogo Público da FunShirt
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [TshirtImageController::class, 'index'])->name('index');
    Route::get('/{tshirtImage}', [TshirtImageController::class, 'show'])->name('show');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('customer/my_images', CustomTshirtImageController::class);

    Route::get('/obter/ficheiro/{my_image}', [CustomTshirtImageController::class, 'file'])
        ->name('my_images.file');
});

// carrinho

//// ----- ROTAS DO CARRINHO (Acesso Público) -----
//Route::get('cart', [CartController::class, 'show'])->name('cart.show');
//Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
//Route::put('cart/{item_key}', [CartController::class, 'updateCart'])->name('cart.update'); // Atualiza qty, cor ou tamanho
//Route::delete('cart/{item_key}', [CartController::class, 'removeFromCart'])->name('cart.remove'); // Botão direto de eliminar
//Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');



Route::get('/cart', [CartController::class, 'show'])
    ->name('cart.show');

Route::post('/cart/add', [CartController::class, 'addToCart'])
    ->name('cart.add');

Route::put('/cart/{itemKey}', [CartController::class, 'updateCart'])
    ->name('cart.update');

Route::delete('/cart/{itemKey}', [CartController::class, 'removeFromCart'])
    ->name('cart.remove');

Route::delete('/cart', [CartController::class, 'destroy'])
    ->name('cart.destroy');

// ----- ROTAS DE CHECKOUT (Apenas Clientes Autenticados) -----
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('checkout', [CartController::class, 'confirm'])->name('cart.confirm');
});

require __DIR__.'/settings.php';

<?php

use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\CustomTshirtImageController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;


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

//Encomendas
//Rotas de checkout(Apenas Clientes Autenticados)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('cart.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('cart.confirm');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
});

// Rotas para alterar o estado da encomenda (Requisito G4)
Route::patch('/orders/{order}/close', [OrderController::class, 'close'])->name('orders.close');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

/*
Como esta rota está dentro do grupo com o middleware auth, o próprio Laravel
encarrega-se de mandar o utilizador anónimo para o Login/Registo de forma automática e,
assim que ele se autenticar, ele regressa ao checkout com o carrinho intacto,
cumprindo a primeira parte do requisito G4
*/


Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add', [CartController::class, 'addToCart'])
    ->name('cart.add');

Route::put('/cart/{itemKey}', [CartController::class, 'updateCart'])
    ->name('cart.update');

Route::delete('/cart/{itemKey}', [CartController::class, 'removeFromCart'])
    ->name('cart.remove');

Route::delete('/cart', [CartController::class, 'destroy'])
    ->name('cart.destroy');



require __DIR__ . '/settings.php';

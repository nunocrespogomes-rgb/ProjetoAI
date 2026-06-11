<?php

use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\CustomTshirtImageController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminColorController;
use App\Http\Controllers\AdminPriceController;

Route::get('/dashboard', function () {
    return redirect('/'); // Redireciona o dashboard para a página inicial (raiz)
})->name('dashboard');

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

// Alterar o estado da encomenda
Route::patch('/orders/{order}/close', [OrderController::class, 'close'])->name('orders.close');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

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


Route::middleware(['auth', 'can:access-profile'])->group(function () {
    require __DIR__ . '/settings.php';
});

// Gestão Funcionários e Admins - Apenas para Administradores
//Route::middleware(['auth'])->group(function () {
//
//    Route::delete('administratives/{administrative}/photo', [App\Http\Controllers\AdministrativeController::class, 'destroyPhoto'])
//        ->name('administratives.photo.destroy');
//
//    Route::patch('administratives/{administrative}/toggle-block', [App\Http\Controllers\AdministrativeController::class, 'toggleBlock'])
//        ->name('administratives.toggle-block');
//
//    Route::resource('administratives', App\Http\Controllers\AdministrativeController::class);
//
//    Route::patch('customers/{customer}/toggle-block', [App\Http\Controllers\AdministrativeController::class, 'toggleBlockCustomer'])
//        ->name('customers.toggle-block');
//
//    Route::get('customers', [App\Http\Controllers\AdministrativeController::class, 'indexCustomers'])
//        ->name('customers.index');
//
//    Route::delete('customers/{customer}', [App\Http\Controllers\AdministrativeController::class, 'destroyCustomer'])
//        ->name('customers.destroy');
//});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
        ->name('administratives.photo.destroy');

    Route::patch('administratives/{administrative}/toggle-block', [AdministrativeController::class, 'toggleBlock'])
        ->name('administratives.toggle-block');

    Route::resource('administratives', AdministrativeController::class);

    Route::patch('customers/{customer}/toggle-block', [AdministrativeController::class, 'toggleBlockCustomer'])
        ->name('customers.toggle-block');

    Route::get('customers', [AdministrativeController::class, 'indexCustomers'])
        ->name('customers.index');

    Route::delete('customers/{customer}', [AdministrativeController::class, 'destroyCustomer'])
        ->name('customers.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('colors', AdminColorController::class)->except(['show']);

    Route::get('prices', [AdminPriceController::class, 'edit'])->name('prices.edit');
    Route::put('prices', [AdminPriceController::class, 'update'])->name('prices.update');
});

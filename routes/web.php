<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CartController;
use App\Models\Student;

/* ----- PUBLIC ROUTES ----- */
Route::view('/', 'home')->name('home');

Route::get('courses/showcase', [CourseController::class, 'showCase'])
    ->name('courses.showcase');

Route::get('courses/{course}/curriculum', [CourseController::class, 'showCurriculum'])
    ->name('courses.curriculum');

/* ----- PROTECTED ROUTES (verified users only) ----- */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('disciplines/my', [DisciplineController::class, 'myDisciplines'])
        ->name('disciplines.my');
    Route::resource('disciplines', DisciplineController::class)->except(['index', 'show']);

    Route::delete('teachers/{teacher}/photo', [TeacherController::class, 'destroyPhoto'])
        ->name('teachers.photo.destroy');
    Route::resource('teachers', TeacherController::class);

    // Student routes
    Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
        ->name('students.photo.destroy');
    Route::resource('students', StudentController::class);

    // Route::delete('students/{student}/photo', [StudentController::class, 'destroyPhoto'])
    //     ->name('students.photo.destroy')
    //     ->can('update', 'student');
    // Route::get('students', [StudentController::class, 'index'])->name('students.index')
    //     ->can('viewAny', Student::class);
    // Route::post('students', [StudentController::class, 'store'])
    //     ->name('students.store')
    //     ->can('create', Student::class);
    // Route::get('students/create', [StudentController::class, 'create'])
    //     ->name('students.create')
    //     ->can('create', Student::class);
    // Route::get('students/{student}', [StudentController::class, 'show'])
    //     ->name('students.show')
    //     ->can('view', 'student');
    // Route::put('students/{student}', [StudentController::class, 'update'])
    //     ->name('students.update')
    //     ->can('update', 'student');
    // Route::delete('students/{student}', [StudentController::class, 'destroy'])
    //     ->name('students.destroy')
    //     ->can('delete', 'student');
    // Route::get('students/{student}/edit', [StudentController::class, 'edit'])
    //     ->name('students.edit')
    //     ->can('update', 'student');


    Route::delete('administratives/{administrative}/photo', [AdministrativeController::class, 'destroyPhoto'])
        ->name('administratives.photo.destroy');
    Route::resource('administratives', AdministrativeController::class);

    // CART Related Routes
    Route::get('cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('cart/{discipline}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('cart/{discipline}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('cart', [CartController::class, 'confirm'])->name('cart.confirm');
    Route::delete('cart', [CartController::class, 'destroy'])->name('cart.destroy');

    // Admin routes
    Route::middleware('can:admin')->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::delete('courses/{course}/image', [CourseController::class, 'destroyImage'])
            ->name('courses.image.destroy');
        Route::resource('courses', CourseController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class);
    });
});

/* ----- OTHER PUBLIC ROUTES ----- */
/* ----- these routes should be positioned after related routes to avoid conflicts ----- */
Route::resource('courses', CourseController::class)->only(['show']);
Route::resource('disciplines', DisciplineController::class)->only(['index', 'show']);

require __DIR__.'/settings.php';

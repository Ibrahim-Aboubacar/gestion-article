<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/art/n', function () {
    dd(str_contains($_SERVER['REQUEST_URI'], 'public'));
});

Route::middleware('auth')->group(function () {
    // affichage
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show')->whereNumber('id');
    // creation
    Route::get('/articles/nouveau', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/article', [ArticleController::class, 'store'])->name('articles.store');
    // modification
    Route::get('/articles/edit/{id}', [ArticleController::class, 'edit'])->name('articles.edit')->whereNumber('id');
    Route::patch('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update')->whereNumber('id');
    // Supression
    Route::delete('/articles/delete/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy')->whereNumber('id');

    // Route relative au profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

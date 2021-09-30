<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/', [PostController::class, 'index'])->name('post.index');
Route::get('/user/{user}/posts', [UserPostController::class, 'index'])->name('post.user.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/create', [PostController::class, 'store'])->name('post.store');
    Route::get('/edit/{post}', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/edit/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/{post}', [PostController::class, 'show'])->name('post.show');

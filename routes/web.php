<?php

use App\Http\Controllers\UrlController;
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

Route::get('/', [UrlController::class, 'create'])->name('urls.create');
Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');
Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
Route::get('/urls/{url}', [UrlController::class, 'show'])->name('url.show');
Route::post('/urls/{url}/checks', [UrlController::class, 'storeCheck'])->name('url_checks.store');

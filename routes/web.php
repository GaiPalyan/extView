<?php

use App\Http\Controllers\DomainController;
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

Route::get('/', [DomainController::class, 'create'])->name('domains.create');
Route::get('/urls', [DomainController::class, 'show'])->name('domains_list.show');
Route::post('/urls/create', [DomainController::class, 'store'])->name('domains.store');
Route::get('/urls/{id}', [DomainController::class, 'domainPage'])->name('domain_personal_page.show');
Route::post('/urls/{id}/checks', [DomainController::class, 'storeCheck'])->name('domain_checks.store');

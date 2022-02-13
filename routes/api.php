<?php

use App\Http\Controllers\ApiUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/urls', [ApiUrlController::class, 'index'])->name('api.index');
Route::post('/urls', [ApiUrlController::class, 'store'])->name('api.store');

Route::get('urls/{url}',[ApiUrlController::class, 'show']);
Route::post('urls/{url}/check', [ApiUrlController::class, 'storeCheck']);
/*Route::group(['middleware' => ['auth:sanctum']], static function () {

});*/

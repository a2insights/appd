<?php

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

Route::get('associados/carteirinhas/validacao/{uuid}', [App\Http\Controllers\CarteirinhaController::class, 'validacao'])
    ->name('associados.carteirinhas.validacao');

Route::get('/', function () {
    return redirect('/admin');
});

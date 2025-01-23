<?php

use Barryvdh\DomPDF\Facade\Pdf;
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

// Route::get('/', function () {
//     $carteirinha = App\Models\Carteirinha::find(32);
//     $pdf = Pdf::loadView('carteirinha', ['carteirinha' => $carteirinha]);
//     $pdf->setPaper([0, 0, 338, 213]);

//     return $pdf->stream();
// });

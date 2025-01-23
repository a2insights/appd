<?php

namespace App\Http\Controllers;

use App\Models\Carteirinha;

class CarteirinhaController extends Controller
{
    public function validacao(string $uuid)
    {
        $carteirinha = Carteirinha::where('uuid', $uuid)->first();

        return view('carteirinha-validacao', ['carteirinha' => $carteirinha]);
    }
}

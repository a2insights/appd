<?php

namespace App\Http\Controllers;

use App\Models\Carteirinha;
use Illuminate\Support\Facades\Gate;

class CarteirinhaController extends Controller
{
    public function previa(?string $uuid = null)
    {
        $carteirinha = Carteirinha::with('associado')
            ->when($uuid !== null, fn ($query) => $query->where('uuid', $uuid))
            ->latest('id')
            ->firstOrFail();

        Gate::authorize('view', $carteirinha);

        return view('carteirinha', [
            'carteirinha' => $carteirinha,
            'backgroundUrl' => asset('images/carteirinha.png'),
        ]);
    }

    public function validacao(string $uuid)
    {
        $carteirinha = Carteirinha::where('uuid', $uuid)->first();

        return view('carteirinha-validacao', ['carteirinha' => $carteirinha]);
    }
}

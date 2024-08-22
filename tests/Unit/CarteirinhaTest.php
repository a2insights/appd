<?php

use App\Models\Associado;
use App\Models\Carteirinha;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

it('can create a carteirinha', function () {
    $associado = Associado::factory()->create();
    $carteirinha = Carteirinha::factory()->create([
        'associado_id' => $associado->id,
    ]);

    assertDatabaseHas('carteirinhas', [
        'associado_id' => $associado->id,
    ]);
});

it('can update a carteirinha', function () {
    $carteirinha = Carteirinha::factory()->create();

    $carteirinha->update(['status' => 'active']);

    assertDatabaseHas('carteirinhas', [
        'status' => 'active',
    ]);
});

it('can delete a carteirinha', function () {
    $carteirinha = Carteirinha::factory()->create();

    $carteirinha->delete();

    assertDatabaseMissing('carteirinhas', [
        'id' => $carteirinha->id,
    ]);
});

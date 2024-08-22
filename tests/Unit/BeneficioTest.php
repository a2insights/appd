<?php

use App\Models\Beneficio;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

it('can create a beneficio', function () {
    $beneficio = Beneficio::factory()->create([
        'nome' => 'Health Benefit',
    ]);

    assertDatabaseHas('beneficios', [
        'nome' => 'Health Benefit',
    ]);
});

it('can update a beneficio', function () {
    $beneficio = Beneficio::factory()->create();

    $beneficio->update(['nome' => 'Transport Benefit']);

    assertDatabaseHas('beneficios', [
        'nome' => 'Transport Benefit',
    ]);
});

it('can delete a beneficio', function () {
    $beneficio = Beneficio::factory()->create();

    $beneficio->delete();

    assertDatabaseMissing('beneficios', [
        'id' => $beneficio->id,
    ]);
});

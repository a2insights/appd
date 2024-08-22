<?php

use App\Models\Associado;
use App\Models\Beneficio;
use App\Models\Carteirinha;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

it('can create an associado', function () {
    $associado = Associado::factory()->create([
        'nome' => 'John Doe',
    ]);

    assertDatabaseHas('associados', [
        'nome' => 'John Doe',
    ]);
});

it('can update an associado', function () {
    $associado = Associado::factory()->create();

    $associado->update(['nome' => 'Jane Doe']);

    assertDatabaseHas('associados', [
        'nome' => 'Jane Doe',
    ]);
});

it('can delete an associado', function () {
    $associado = Associado::factory()->create();

    $associado->delete();

    assertDatabaseMissing('associados', [
        'id' => $associado->id,
    ]);
});

it('can retrieve associated carteirinhas', function () {
    $associado = Associado::factory()->create();
    Carteirinha::factory()->create(['associado_id' => $associado->id]);

    expect($associado->carteirinhas)->toHaveCount(1);
});

it('can retrieve associated beneficios', function () {
    $associado = Associado::factory()->create();
    $beneficio = Beneficio::factory()->create();

    $associado->beneficios()->attach($beneficio->id);

    expect($associado->beneficios)->toHaveCount(1);
});

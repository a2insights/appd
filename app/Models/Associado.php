<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Associado extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'foto',
        'nome',
        'status',
        'data_nascimento',
        'nome_social',
        'sexo',
        'declaracao_sexual',
        'cpf',
        'rg',
        'orgao_expedidor',
        'orgao_expedidor_uf',
        'estado_civil',
        'certidao_nascimento',
        'naturalidade_ibge',
        'mae',
        'pai',
        'religiao',
        'escolaridade',
        'raca',
        'cid10',
        'crm',
        'causa_deficiencia',
        'tipo_deficiencia',
        'aparelho_utilizado',
        'cep',
        'ocupacao',
        'rua',
        'bairro',
        'numero',
        'estado',
        'cidade',
        'perimetro',
        'telefone_celular',
        'telefone_whatsapbigintp',
        'telefone_fixo',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'data_nascimento' => 'date',
        'cid10' => 'array',
    ];

    public function carteirinhas(): HasMany
    {
        return $this->hasMany(Carteirinha::class);
    }

    public function beneficios(): BelongsToMany
    {
        return $this->belongsToMany(Beneficio::class);
    }
}

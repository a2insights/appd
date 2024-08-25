<?php

namespace App\Models;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\OrgaoExpedidor;
use App\OrgaoExpedidorUf;
use App\Raca;
use App\Religiao;
use App\TipoDeficiencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Associado extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

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
        'naturalidade_uf',
        'naturalidade_municipio_ibge',
        'mae',
        'pai',
        'religiao',
        'ocupacao',
        'escolaridade',
        'raca',
        'cid10',
        'crm',
        'causa_deficiencia',
        'tipo_deficiencia',
        'aparelho_utilizado',
        'cep',
        'rua',
        'bairro',
        'numero',
        'estado',
        'cidade',
        'perimetro',
        'telefone_celular',
        'telefone_whatsapp',
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
        'status' => AssociadoStatus::class,
        'data_nascimento' => 'date',
        'declaracao_sexual' => DeclaracaoSexual::class,
        'orgao_expedidor' => OrgaoExpedidor::class,
        'orgao_expedidor_uf' => OrgaoExpedidorUf::class,
        'estado_civil' => EstadoCivil::class,
        'naturalidade_uf' => NaturalidadeUf::class,
        'religiao' => Religiao::class,
        'ocupacao' => Ocupacao::class,
        'escolaridade' => Escolaridade::class,
        'raca' => Raca::class,
        'causa_deficiencia' => CausaDeficiencia::class,
        'tipo_deficiencia' => TipoDeficiencia::class,
        'aparelho_utilizado' => AparelhoUtilizado::class,
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

    public function cid10(): \Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson
    {
        return $this->belongsToJson(Cid10::class, 'cid10');
    }
}

<?php

namespace App\Models;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\Casts\Set;
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
use App\Sexo;
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
     * The attributes that be default.
     *
     * @var array
     */
    public $attributes = [
        'cid10' => '[]',
    ];

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
        'ocupacoes',
        'escolaridade',
        'raca',
        'cid10',
        'crm',
        'causa_deficiencia',
        'tipo_deficiencia',
        'aparelhos_utilizado',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'status' => AssociadoStatus::class,
            'data_nascimento' => 'date',
            'sexo' => Sexo::class,
            'declaracao_sexual' => DeclaracaoSexual::class,
            'orgao_expedidor' => OrgaoExpedidor::class,
            'orgao_expedidor_uf' => OrgaoExpedidorUf::class,
            'estado_civil' => EstadoCivil::class,
            'naturalidade_uf' => NaturalidadeUf::class,
            'religiao' => Religiao::class,
            // 'ocupacoes' => Ocupacao::class,
            'ocupacoes' => Set::class,
            'escolaridade' => Escolaridade::class,
            'raca' => Raca::class,
            'causa_deficiencia' => CausaDeficiencia::class,
            'tipo_deficiencia' => TipoDeficiencia::class,
            // 'aparelhos_utilizado' => AparelhoUtilizado::class,
            'aparelhos_utilizado' => Set::class,
            'cid10' => 'array',
        ];
    }

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

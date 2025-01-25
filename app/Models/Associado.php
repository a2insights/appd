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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Overtrue\LaravelVersionable\VersionStrategy;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Associado extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use \Overtrue\LaravelVersionable\Versionable;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $versionable = [
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

    protected $versionStrategy = VersionStrategy::SNAPSHOT;

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
     * Get the dates that should be cast.
     *
     * @return array<string, string>
     */
    protected $dates = [
        'data_nascimento',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($associado) {
            $associado->carteirinhas()->delete();
            $associado->beneficios()->detach();
            // Don't need to delete media, it will be deleted automatically by spatie/laravel-medialibrary
            //  $associado->media()->delete();
        });

        static::deleted(function ($carteirinha) {
            $disk = config('filament.default_filesystem_disk');

            if ($carteirinha->foto) {
                Storage::disk($disk)->delete($carteirinha->foto);
            }
        });
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->foto) {
                return null;
            }

            $disk = config('filament.default_filesystem_disk');
            $fotoPath = $this->foto;

            if ($disk === 's3') {
                $s3Disk = Storage::disk('s3');
                if ($s3Disk->exists($fotoPath)) {
                    return Cache::remember("foto_url_{$this->id}", now()->addDays(7), function () use ($disk) {
                        $disk = Storage::disk($disk);

                        return $disk->temporaryUrl($this->foto, now()->addDays(7));
                    });
                }
            }

            return Storage::disk($disk)->url($fotoPath);
        });
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
        return $this->belongsToJson(Cid10::class, 'cid10', 'codigo');
    }

    public function getDocumento()
    {
        return $this->rg
            ? 'RG: '.$this->rg
            : ($this->cpf
                ? 'CPF: '.$this->cpf
                : ($this->certidao_de_nascimento
                    ? 'Ct/Nasc: '.$this->certidao_de_nascimento
                    : null));
    }

    public function abbreviateName()
    {
        $name = $this->nome;

        $prepositions = [
            'de', 'da', 'do', 'das', 'dos',
        ];

        $splitName = explode(' ', $name);

        if (Str::length($name) > 60) {
            // Abrevia os nomes intermediários, começando pelos últimos
            for ($i = count($splitName) - 2; $i > 0; $i--) {
                if (! in_array(strtolower($splitName[$i]), $prepositions)) {
                    $splitName[$i] = Str::substr($splitName[$i], 0, 1).'.';
                }

                // Verifica se já está dentro do limite
                if (Str::length(implode(' ', $splitName)) <= 55) {
                    break;
                }
            }
        }

        return implode(' ', $splitName);
    }
}

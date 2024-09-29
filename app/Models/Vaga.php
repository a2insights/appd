<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vaga extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ativa',
        'titulo',
        'descricao',
        'requisitos',
        'inicia_em',
        'finaliza_em',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ativa' => 'boolean',
        'inicia_em' => 'datetime',
        'finaliza_em' => 'datetime',
    ];

    public function competencias(): BelongsToMany
    {
        return $this->belongsToMany(Competencia::class);
    }

    public function talentos(): HasMany
    {
        return $this->hasMany(Candidato::class);
    }
}

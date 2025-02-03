<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Atendimento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'em_andamento',
        'finalizado_automaticamente',
        'pessoa_id',
        'associado_id',
        'finalizado_em',
        'declaracao',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'declaracao' => 'array',
    ];

    public function tipos(): BelongsToMany
    {
        return $this->belongsToMany(Tipo::class, 'atendimento_tipo');
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function associado(): BelongsTo
    {
        return $this->belongsTo(Associado::class);
    }

    public function getNome(): string
    {
        return $this->associado->nome ?? $this->pessoa->nome ?? 'Nome não informado';
    }
}

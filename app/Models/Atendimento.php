<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Atendimento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_id',
        'em_andamento',
        'finalizado_automaticamente',
        'pessoa_id',
        'associado_id',
        'finalizado_em',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo_id' => 'integer',
        'em_andamento' => 'boolean',
        'finalizado_automaticamente' => 'boolean',
        'pessoa_id' => 'integer',
        'associado_id' => 'integer',
        'finalizado_em' => 'datetime',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function associado(): BelongsTo
    {
        return $this->belongsTo(Associado::class);
    }
}

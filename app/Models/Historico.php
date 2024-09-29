<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historico extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acao_id',
        'atendimento_id',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'acao_id' => 'integer',
        'atendimento_id' => 'integer',
        'data' => 'array',
    ];

    public function acao(): BelongsTo
    {
        return $this->belongsTo(Aco::class);
    }

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(Atendimento::class);
    }
}

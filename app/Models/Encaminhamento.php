<?php

namespace App\Models;

use App\EncaminhamentoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Encaminhamento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vaga_id',
        'talento_id',
        'status',
        'encaminhamento',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => EncaminhamentoStatus::class,
        'encaminhamento' => 'array',
    ];

    public function vaga(): BelongsTo
    {
        return $this->belongsTo(Vaga::class);
    }

    public function talento(): BelongsTo
    {
        return $this->belongsTo(Talento::class);
    }
}

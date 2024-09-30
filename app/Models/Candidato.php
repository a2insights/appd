<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidato extends Model
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

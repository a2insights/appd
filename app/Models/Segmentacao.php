<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segmentacao extends Model
{
    protected $table = 'segmentacoes';

    protected $fillable = [
        'name',
        'filters',
        'grupo_segmentacao_id',
    ];

    public function grupo()
    {
        return $this->belongsTo(GrupoSegmentacao::class, 'grupo_segmentacao_id');
    }

    protected $casts = [
        'filters' => 'array',
    ];
}

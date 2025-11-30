<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoSegmentacao extends Model
{
    protected $table = 'grupos_segmentacao';

    protected $fillable = [
        'name',
        'description',
    ];

    public function segmentacoes()
    {
        return $this->hasMany(Segmentacao::class, 'grupo_segmentacao_id');
    }
}

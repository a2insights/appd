<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segmentacao extends Model
{
    protected $table = 'segmentacoes';

    protected $fillable = [
        'name',
        'filters',
    ];

    protected $casts = [
        'filters' => 'array',
    ];
}

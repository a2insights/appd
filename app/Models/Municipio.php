<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipio extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'codigo_ibge';

    protected $keyType = 'int';

    public $incrementing = false;

    protected $fillable = ['codigo_ibge', 'nome', 'estado_codigo_ibge'];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado_codigo_ibge', 'codigo_ibge');
    }
}

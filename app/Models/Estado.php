<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'codigo_ibge';

    protected $keyType = 'int';

    public $incrementing = false;

    protected $fillable = ['codigo_ibge', 'uf', 'nome'];

    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class, 'estado_codigo_ibge', 'codigo_ibge');
    }
}

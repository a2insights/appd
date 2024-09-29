<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competencia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    public function talentos(): BelongsToMany
    {
        return $this->belongsToMany(Talento::class, 'competencia_talento');
    }

    public function vagas(): BelongsToMany
    {
        return $this->belongsToMany(Vaga::class, 'competencia_vaga');
    }
}

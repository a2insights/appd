<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cargo extends Model
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
        return $this->belongsToMany(Talento::class, 'Cargo_talento');
    }

    public function vagas(): BelongsToMany
    {
        return $this->belongsToMany(Vaga::class, 'Cargo_vaga');
    }
}

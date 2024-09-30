<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'associado_id',
    ];

    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'cargo_talento');
    }

    public function associado(): BelongsTo
    {
        return $this->belongsTo(Associado::class);
    }
}

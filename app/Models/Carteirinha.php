<?php

namespace App\Models;

use App\CarteirinhaStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Carteirinha extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'associado_id',
        'foto',
        'status',
        'data_emissao',
        'data_vencimento',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => CarteirinhaStatus::class,
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($carteirinha) {
            $disk = config('filament.default_filesystem_disk');

            Storage::disk($disk)->delete($carteirinha->foto);
        });
    }

    public function associado(): BelongsTo
    {
        return $this->belongsTo(Associado::class);
    }
}

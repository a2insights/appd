<?php

namespace App\Models;

use App\CarteirinhaStatus;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Barryvdh\DomPDF\Facade\Pdf;
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
        'short_url_id',
        'foto',
        'pdf',
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

            if ($carteirinha->foto) {
                Storage::disk($disk)->delete($carteirinha->foto);
            }
        });

        static::saved(function ($carteirinha) {
            $disk = config('filament.default_filesystem_disk');

            $pdfPath = @array_values($carteirinha->pdf ?? [])[0] ?? $carteirinha->pdf;
            if ($pdfPath) {
                Storage::disk($disk)->delete($pdfPath);
            }

            $template = view('carteirinha')->with(['carteirinha' => $carteirinha]);

            $pdf = Pdf::loadHTML($template);
            $pdf->setPaper([0, 0, 338, 213]);

            $path = 'carteirinhas/'.uniqid().'.pdf';

            Storage::disk($disk)->put($path, $pdf->output());

            $carteirinha->pdf = $path;
            $carteirinha->saveQuietly();
        });
    }

    public function associado(): BelongsTo
    {
        return $this->belongsTo(Associado::class);
    }

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortURL::class);
    }
}

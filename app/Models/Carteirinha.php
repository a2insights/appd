<?php

namespace App\Models;

use App\CarteirinhaStatus;
use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Models\ShortURL;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Carteirinha extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
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

        static::creating(function ($carteirinha) {
            $uuid = Str::uuid();
            $carteirinha->uuid = $uuid;

            $shortURLObject = app(Builder::class)
                ->destinationUrl(route('associados.carteirinhas.validacao', $uuid))
                ->trackIPAddress()
                ->trackVisits()
                ->trackBrowserVersion()
                ->trackOperatingSystem()
                ->trackOperatingSystemVersion()
                ->trackDeviceType()
                ->trackRefererURL()
                ->make();

            $carteirinha->short_url_id = $shortURLObject->id;
        });

        static::saved(function ($carteirinha) {
            $disk = config('filament.default_filesystem_disk');

            $pdfPath = is_array($carteirinha->pdf ?? null)
                ? array_values($carteirinha->pdf)[0] ?? null
                : $carteirinha->pdf;
            if ($pdfPath) {
                Storage::disk($disk)->delete($pdfPath);
            }

            $qrcodePath = 'qrcodes/'.$carteirinha->uuid.'.svg';
            Storage::disk($disk)->put($qrcodePath, $carteirinha->getQrCodeSvg());

            $pdf = Pdf::loadView('carteirinha', ['carteirinha' => $carteirinha]);
            $pdf->setPaper([0, 0, 338, 213]);

            $path = 'carteirinhas/'.uniqid().'.pdf';

            Storage::disk($disk)->put($path, $pdf->output());

            $carteirinha->pdf = $path;
            $carteirinha->saveQuietly();
        });
    }

    public function getQrCodeSvgUrl()
    {
        $disk = config('filesystems.default');

        $qrcodePath = 'qrcodes/'.$this->uuid.'.svg';

        if ($disk === 's3') {
            $s3Disk = Storage::disk('s3');
            if ($s3Disk->exists($qrcodePath)) {
                return $s3Disk->temporaryUrl($qrcodePath, now()->addMinutes(5));
            }
        }

        return Storage::disk(config('filament.default_filesystem_disk'))->url($qrcodePath);
    }

    public function getQrCodeSvgDataUriImage()
    {
        $svg = $this->getQrCodeSvg();

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function getQrCodeSvg()
    {
        $url = route('associados.carteirinhas.validacao', $this->uuid);

        return (new Writer(
            new ImageRenderer(
                new RendererStyle(50, 1, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($url);
    }

    public function getFotoUrl()
    {
        $disk = config('filesystems.default');
        $fotoPath = $this->foto;

        if ($disk === 's3') {
            $s3Disk = Storage::disk('s3');
            if ($s3Disk->exists($fotoPath)) {
                return $s3Disk->temporaryUrl($fotoPath, now()->addMinutes(5));
            }
        }

        return Storage::disk(config('filament.default_filesystem_disk'))->url($fotoPath);
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

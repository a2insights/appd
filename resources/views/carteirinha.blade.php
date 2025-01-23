@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $associado = $carteirinha->associado;

    $documento = $associado->getDocumento();

    $fotoUrl = $carteirinha->getFotoUrl();

    $nome = $associado->abbreviateName();
@endphp

<body style="width: 388px;height: 244px; position: relative; background-size: cover; background-repeat: no-repeat;">
    <div
        style="position: absolute; bottom: 44px; left: -35px; font-size: 14px; font-family: sans-serif; line-height: 2;">
        <h4 style="margin: 0;">{{ $nome }}</h4>
        <h4 style="margin: 0;">{{ $documento }}</h4>
        <h4 style="margin: 0;">Validade: {{ $carteirinha->data_vencimento->format('d/m/Y') }}</h4>
    </div>
    @if (isset($carteirinha->shortUrl->default_short_url))
        <div style="position: absolute; right: 100px; top: 142px; width: 70px; height: 70px;">
            {{-- <p style="margin: 0; text-align: center;font-size: 10px;">
                <small><strong>QR Code</strong></small>
            </p> --}}
            <img style="width: 70px; height: 70px;" src="{{ $carteirinha->getQrCodeSvgUrl() }}">
            {{-- <p
                style="margin: 0; text-align: center; font-size: 8px; max-width: 100%; word-wrap: break-word; overflow-wrap: break-word;">
                {{ $carteirinha->shortUrl->default_short_url }}
            </p> --}}
        </div>
    @endif
    <div style="position: absolute; right: 10px;top: 127px;width: 75px;height: 100px; ">
        <img alt="foto" style="width: 75px; height: 100px;" src="{{ $fotoUrl }}">
    </div>
</body>

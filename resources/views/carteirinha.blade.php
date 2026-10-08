@php
$associado = $carteirinha->associado;
@endphp
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }

        html,
        body {
            width: 85.6mm;
            height: 54mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .card {
            position: relative;
            width: 85.6mm;
            height: 54mm;
            overflow: hidden;
            font-family: DejaVu Sans, sans-serif;
            color: #111;
        }

        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .qr-code,
        .photo {
            position: absolute;
        }

        .details {
            position: absolute;
            bottom: 7mm;
            left: 4mm;
            width: 48mm;
            font-size: 7.5pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .name {
            white-space: nowrap;
        }

        .name,
        .document,
        .validity {
            margin: 0;
            line-height: inherit;
        }

        .qr-code {
            bottom: 5mm;
            left: 52mm;
            width: 13mm;
            height: 13mm;
            padding: 0.8mm;
            box-sizing: border-box;
            background: #fff;
        }

        .qr-code img {
            display: block;
            width: 65px;
            height: 65px;
        }

        .photo {
            top: 113px;
            left: 248.5px;
            width: 60.5px;
            height: 79.5px;
        }

        .photo img {
            display: block;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <main class="card">
        @isset($backgroundUrl)
        <img class="card-background" src="{{ $backgroundUrl }}" alt="">
        @endisset
        <section class="details">
            <div class="name">{{ $associado->abbreviateName(28) }}</div>
            <div class="document">{{ $associado->getDocumento() }}</div>
            <div class="validity">VALIDADE: {{ $carteirinha->data_vencimento->format('d/m/Y') }}</div>
        </section>
        <div class="qr-code">
            <img style="width: 45px; height:45px;" src="{{ $carteirinha->getQrCodeSvgUrl() }}" alt="QR Code">
        </div>
        @if ($carteirinha->foto)
        <div class="photo"><img src="{{ $carteirinha->foto_url }}" alt="Foto"></div>
        @endif
    </main>
</body>

</html>
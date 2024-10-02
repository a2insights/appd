@php
    $associado = $carteirinha->associado;

    $documento = getDocumento($associado);

    $fotoUrl = getFotoUrl($carteirinha);

    $nome = abbreviateName($associado->nome);
@endphp

<body style="width: 388px;height: 244px; position: relative; background-size: cover; background-repeat: no-repeat;">
    <div style="position: absolute; bottom: 30px; left: -30px; font-size: 20px; font-family: sans-serif;">
        <h4 style="margin: 0;">{{ $nome }}</h4>
        <h4 style="margin: 0;">{{ $documento }}</h4>
        <h4 style="margin: 0;">Validade: {{ $carteirinha->data_vencimento->format('d/m/Y') }}</h4>
    </div>
    <div style="position: absolute; right: -15px;top: 127px;width: 88px;height: 101px; ">
        <img alt="foto" style="width: 75px; height: 100px;" src="{{ $fotoUrl }}">
    </div>
</body>

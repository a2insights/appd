@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    function getDocumento($associado)
    {
        return $associado->rg
            ? 'RG: ' . $associado->rg
            : ($associado->cpf
                ? 'CPF: ' . $associado->cpf
                : ($associado->certidao_de_nascimento
                    ? 'Ct/Nasc: ' . $associado->certidao_de_nascimento
                    : null));
    }

    function getFotoUrl($carteirinha)
    {
        $disk = config('filesystems.default');
        $fotoPath = $carteirinha->foto;

        // Check if the storage is s3, and handle accordingly
        if ($disk === 's3') {
            $s3Disk = Storage::disk('s3');
            if ($s3Disk->exists($fotoPath)) {
                return $s3Disk->temporaryUrl($fotoPath, now()->addMinutes(5));
            }
        }

        // For local storage or other disks
        return Storage::disk(config('filament.default_filesystem_disk'))->url($fotoPath);
    }

    function abbreviateName($name)
    {
        $splitName = explode(' ', $name);

        if (count($splitName) >= 3 && Str::length($name) > 24) {
            for ($i = 1; $i < count($splitName) - 1; $i++) {
                $splitName[$i] =
                    $i === count($splitName) - 2 ? $splitName[$i] : Str::substr($splitName[$i], 0, 1) . '.';
            }
            return implode(' ', $splitName);
        }

        return $name;
    }

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

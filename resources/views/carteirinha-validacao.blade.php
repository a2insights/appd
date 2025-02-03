<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Carteirinha</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="absolute top-0 left-0 w-full p-4">
        <div class="flex flex-col items-center justify-center max-w-3xl mx-auto text-center">
            <img src="{{ url('img/logo.svg') }}" alt="Logo APPD" class="w-12 h-12 rounded-full">
            <h1 class="text-lg font-bold">
                <span class="text-sm font-normal">Associação Paraense das Pessoas com Deficiência (APPD)</span>
                <br>
                Verificação de Carteirinha
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    @if ($carteirinha)
        <div class="relative w-full max-w-lg p-6 mt-16 bg-white rounded-lg shadow-md">
            <!-- Overlay -->
            @if ($carteirinha->status->value === 'cancelada')
                <div class="absolute inset-0 flex items-center justify-center bg-red-500 bg-opacity-50 rounded-lg">
                    <span class="text-2xl font-bold text-white">CANCELADA</span>
                </div>
            @endif
            @if ($carteirinha->status->value === 'vencida')
                <div class="absolute inset-0 flex items-center justify-center bg-yellow-500 bg-opacity-50 rounded-lg">
                    <span class="text-2xl font-bold text-white">VENCIDA</span>
                </div>
            @endif

            <div class="flex items-start">
                <div class="flex-1">
                    <h1 class="mb-4 text-xl font-semibold text-gray-800">
                        {{ $carteirinha->associado->nome ?? 'Nome não disponível' }}
                    </h1>
                    @php
                        $statusClasses = match ($carteirinha->status->value) {
                            'ativa' => 'text-green-700',
                            'vencida' => 'text-yellow-700',
                            'cancelada' => 'text-red-700',
                            default => 'text-gray-700',
                        };
                    @endphp
                    <p class="text-sm mt-1 text-gray-600 {{ $statusClasses }}">
                        <strong>Status:</strong> {{ $carteirinha->status }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        <strong>Validade:</strong> {{ $carteirinha->data_vencimento->format('d/m/Y') }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600">
                        <strong>Deficiência:</strong> {{ $carteirinha->associado->tipo_deficiencia }}
                    </p>
                </div>

                <div class="ml-6">
                    <img src="{{ $carteirinha->foto_url ?? 'https://via.placeholder.com/150' }}" alt="Foto do associado"
                        class="w-24 rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    @else
        <div class="w-full max-w-md p-6 mt-16 bg-red-100 rounded-lg shadow-md">
            <div class="text-center">
                <h1 class="text-xl font-semibold text-red-800">
                    Carteirinha não encontrada ou inválida
                </h1>
                <p class="mt-2 text-sm text-red-600">
                    Por favor, verifique as informações fornecidas e tente novamente.
                </p>
            </div>
        </div>
    @endif
</body>

</html>

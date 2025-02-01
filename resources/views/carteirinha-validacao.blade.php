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
    <header class="absolute top-0 left-0 w-full p-4 text-white bg-blue-500 shadow-md">
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
        <div class="w-full max-w-md p-6 mt-16 bg-white rounded-lg shadow-md">
            <div class="flex items-start">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-gray-800">
                        {{ $carteirinha->associado->nome ?? 'Nome não disponível' }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        <strong>Validade:</strong> {{ $carteirinha->data_vencimento->format('d/m/Y') }}
                    </p>
                    <div class="mt-4">
                        @php
                            $statusClasses = match ($carteirinha->status ?? 'cancelada') {
                                'ativa' => 'bg-green-100 text-green-700',
                                'vencida' => 'bg-yellow-100 text-yellow-700',
                                'cancelada' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="block text-sm font-medium rounded-lg p-2 {{ $statusClasses }}">
                            <strong>Status:</strong> {{ $carteirinha->status ?? 'Status não disponível' }}
                        </span>
                    </div>
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

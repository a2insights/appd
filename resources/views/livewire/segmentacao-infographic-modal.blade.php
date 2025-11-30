<div class="p-6 bg-gray-50 dark:bg-gray-950 print:bg-white print:p-0">
    <!-- Print Styles -->
    <style>
        @media print {
            @page { margin: 0.5cm; size: A4; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-break-inside-avoid { break-inside: avoid; }
            .print-text-black { color: black !important; }
            .print-bg-white { background-color: white !important; }
        }
    </style>

    <!-- Header -->
    <div class="flex justify-between items-center mb-4 border-b pb-4 dark:border-gray-800">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white print-text-black">Relatório de Segmentação</h2>
            <p class="text-gray-500 dark:text-gray-400 print-text-black">Gerado em {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Encontrado</div>
            <div class="text-4xl font-extrabold text-primary-600 dark:text-primary-500 print-text-black">{{ number_format($stats['total'] ?? 0, 0, ',', '.') }}</div>
            <div class="text-xs text-gray-400">Associados</div>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(!empty($activeFilters))
        <div class="mb-6 p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 print:border-gray-300">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Filtros Aplicados:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($activeFilters as $filter)
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 print:bg-gray-200 print:text-black border border-primary-200 dark:border-primary-800">
                        <span class="font-bold mr-1">{{ $filter['label'] }}:</span>
                        {{ $filter['value'] }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(($stats['total'] ?? 0) > 0)
        <x-infographic-content :stats="$stats" />
    @else
        <div class="text-center py-12">
            <x-heroicon-o-document-magnifying-glass class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhum resultado encontrado</h3>
            <p class="text-gray-500 dark:text-gray-400">Ajuste os filtros para visualizar o infográfico.</p>
        </div>
    @endif
    <style>
        .fi-topbar {
            z-index: 10 !important;
        }
    </style>

</div>

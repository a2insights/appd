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
                @foreach($activeFilters as $key => $value)
                    @if(!blank($value) && $value !== 'all')
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200 print:bg-gray-200 print:text-black border border-primary-200 dark:border-primary-800">
                            <span class="font-bold mr-1">{{ str($key)->title()->replace('_', ' ') }}:</span>
                            @if(is_array($value))
                                {{ implode(', ', $value) }}
                            @else
                                {{ $value }}
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @if(($stats['total'] ?? 0) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:block">
            
            <!-- Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Sexo -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-user-group class="w-5 h-5" />
                    Distribuição por Sexo
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js(array_keys($stats['sexo'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['sexo'] ?? [])),
                                    backgroundColor: ['#3b82f6', '#ec4899', '#9ca3af', '#10b981', '#f59e0b'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' }
                                    }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Faixa Etária -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-cake class="w-5 h-5" />
                    Faixa Etária
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['faixa_etaria'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['faixa_etaria'] ?? [])),
                                    backgroundColor: '#8b5cf6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y + ' Associados';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb' },
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' }
                                    }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Estado Civil -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    Estado Civil
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'pie',
                            data: {
                                labels: @js(array_keys($stats['estado_civil'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['estado_civil'] ?? [])),
                                    backgroundColor: [
                                        '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981', 
                                        '#06b6d4', '#3b82f6', '#6366f1', '#8b5cf6', '#d946ef'
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: { 
                                            boxWidth: 12,
                                            color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' 
                                        }
                                    }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Localização (Top 5 Estados) -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-map-pin class="w-5 h-5" />
                    Top Localizações (Estado)
                </h3>
                <div class="space-y-3">
                    @php
                        $maxState = max($stats['estado'] ?? [0]);
                    @endphp
                    @foreach(array_slice($stats['estado'] ?? [], 0, 5) as $label => $count)
                        @php
                            $width = $maxState > 0 ? ($count / $maxState) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-8 text-sm font-bold text-gray-600 dark:text-gray-300 print-text-black">{{ $label ?: 'N/A' }}</div>
                            <div class="flex-1 bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-100">
                                <div class="bg-green-500 h-2 rounded-full print:bg-gray-600" style="width: {{ $width }}%"></div>
                            </div>
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 print-text-black">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

             <!-- Deficiências -->
             @if(!empty($stats['tipo_deficiencia']))
             <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 md:col-span-2 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                 <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                     <x-heroicon-o-hand-raised class="w-5 h-5" />
                     Tipos de Deficiência
                 </h3>
                 <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                     @foreach($stats['tipo_deficiencia'] ?? [] as $label => $count)
                         <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 print:border-gray-300 print:bg-white">
                             <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate mr-2 print-text-black" title="{{ $label }}">{{ $label ?: 'Não informado' }}</span>
                             <span class="px-2 py-1 text-xs font-bold text-white bg-primary-500 rounded-full print:bg-gray-800 print:text-white">{{ $count }}</span>
                         </div>
                     @endforeach
                 </div>
             </div>
             @endif

        </div>
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

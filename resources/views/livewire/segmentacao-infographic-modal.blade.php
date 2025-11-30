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

            <!-- SEÇÃO IDENTIFICAÇÃO SOCIAL -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Identificação Social</h3>
            </div>

            <!-- Raça/Cor -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-swatch class="w-5 h-5" />
                    Raça/Cor
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js(array_keys($stats['raca'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['raca'] ?? [])),
                                    backgroundColor: ['#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#6b7280'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
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

            <!-- Declaração Sexual -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    Declaração Sexual
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'pie',
                            data: {
                                labels: @js(array_keys($stats['declaracao_sexual'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['declaracao_sexual'] ?? [])),
                                    backgroundColor: ['#f43f5e', '#8b5cf6', '#0ea5e9', '#10b981', '#f59e0b', '#6b7280'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
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

            <!-- Escolaridade -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-academic-cap class="w-5 h-5" />
                    Escolaridade
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['escolaridade'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['escolaridade'] ?? [])),
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Religião -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-star class="w-5 h-5" />
                    Religião
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['religiao'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['religiao'] ?? [])),
                                    backgroundColor: '#10b981',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- SEÇÃO SAÚDE E DEFICIÊNCIA -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Saúde e Deficiência</h3>
            </div>

            <!-- Tipo de Deficiência -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-eye class="w-5 h-5" />
                    Tipo de Deficiência
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js(array_keys($stats['tipo_deficiencia'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['tipo_deficiencia'] ?? [])),
                                    backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981', '#06b6d4'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
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

            <!-- Causa da Deficiência -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-question-mark-circle class="w-5 h-5" />
                    Causa da Deficiência
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['causa_deficiencia'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['causa_deficiencia'] ?? [])),
                                    backgroundColor: '#8b5cf6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Aparelhos Utilizados -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-wrench-screwdriver class="w-5 h-5" />
                    Aparelhos Utilizados (Top 10)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['aparelhos_utilizado'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['aparelhos_utilizado'] ?? [])),
                                    backgroundColor: '#ec4899',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- CID-10 -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                    Diagnósticos (CID-10) (Top 10)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['cid10'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['cid10'] ?? [])),
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- SEÇÃO PROFISSIONAL E BENEFÍCIOS -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Profissional e Benefícios</h3>
            </div>

            <!-- Ocupações -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-briefcase class="w-5 h-5" />
                    Ocupações (Top 10)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['ocupacoes'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['ocupacoes'] ?? [])),
                                    backgroundColor: '#f59e0b',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Benefícios -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-gift class="w-5 h-5" />
                    Benefícios Utilizados (Top 10)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['beneficios'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['beneficios'] ?? [])),
                                    backgroundColor: '#10b981',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- SEÇÃO DADOS DA ASSOCIAÇÃO -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Dados da Associação</h3>
            </div>

            <!-- Status -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    Status do Associado
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'doughnut',
                            data: {
                                labels: @js(array_keys($stats['status'] ?? [])),
                                datasets: [{
                                    data: @js(array_values($stats['status'] ?? [])),
                                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#6b7280'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
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

            <!-- Tempo de Associação -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-clock class="w-5 h-5" />
                    Tempo de Associação
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['tempo_associacao'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['tempo_associacao'] ?? [])),
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- SEÇÃO NATURALIDADE -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Naturalidade</h3>
            </div>

            <!-- Naturalidade UF -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-map class="w-5 h-5" />
                    Naturalidade (UF)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['naturalidade_uf'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['naturalidade_uf'] ?? [])),
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Naturalidade Município -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-building-library class="w-5 h-5" />
                    Naturalidade (Top 10 Municípios)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['naturalidade_municipio'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['naturalidade_municipio'] ?? [])),
                                    backgroundColor: '#10b981',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- SEÇÃO LOCALIZAÇÃO ATUAL -->
            <div class="md:col-span-2 mt-4 mb-2">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Localização Atual</h3>
            </div>

            <!-- Endereço UF -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-map-pin class="w-5 h-5" />
                    Estado (UF)
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['endereco_uf'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['endereco_uf'] ?? [])),
                                    backgroundColor: '#f59e0b',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Endereço Cidade -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-building-office-2 class="w-5 h-5" />
                    Top 10 Cidades
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['endereco_cidade'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['endereco_cidade'] ?? [])),
                                    backgroundColor: '#8b5cf6',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Endereço Bairro -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 md:col-span-2 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-home-modern class="w-5 h-5" />
                    Top 10 Bairros
                </h3>
                <div class="relative h-64" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js(array_keys($stats['endereco_bairro'] ?? [])),
                                datasets: [{
                                    label: 'Associados',
                                    data: @js(array_values($stats['endereco_bairro'] ?? [])),
                                    backgroundColor: '#ec4899',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } },
                                    y: { grid: { display: false }, ticks: { color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#374151' } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
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

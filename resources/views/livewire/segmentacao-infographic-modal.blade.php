<div class="p-6 bg-gray-50 dark:bg-gray-950 min-h-screen print:bg-white print:p-0">
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
    <div class="flex justify-between items-center mb-8 border-b pb-4 dark:border-gray-800">
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

    @if(($stats['total'] ?? 0) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:block">
            
            <!-- Sexo -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-user-group class="w-5 h-5" />
                    Distribuição por Sexo
                </h3>
                <div class="space-y-4">
                    @php
                        $totalSexo = array_sum($stats['sexo'] ?? []);
                        $colors = ['Masculino' => 'bg-blue-500', 'Feminino' => 'bg-pink-500', 'Outro' => 'bg-gray-500'];
                    @endphp
                    @foreach($stats['sexo'] ?? [] as $label => $count)
                        @php 
                            $percentage = $totalSexo > 0 ? ($count / $totalSexo) * 100 : 0;
                            $labelClean = $label ?: 'Não informado';
                            $color = match(Str::lower($labelClean)) {
                                'masculino', 'm' => 'bg-blue-500',
                                'feminino', 'f' => 'bg-pink-500',
                                default => 'bg-gray-400'
                            };
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300 print-text-black">{{ $labelClean }}</span>
                                <span class="text-gray-500 dark:text-gray-400 print-text-black">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-800 print:bg-gray-200">
                                <div class="{{ $color }} h-2.5 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Faixa Etária -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-cake class="w-5 h-5" />
                    Faixa Etária
                </h3>
                <div class="flex items-end justify-between h-40 gap-2 pt-4">
                    @php
                        $maxAge = max($stats['faixa_etaria'] ?? [0]);
                        $totalAge = array_sum($stats['faixa_etaria'] ?? []);
                    @endphp
                    @foreach(['0-17', '18-29', '30-49', '50-64', '65+'] as $range)
                        @php
                            $count = $stats['faixa_etaria'][$range] ?? 0;
                            $height = $maxAge > 0 ? ($count / $maxAge) * 100 : 0;
                        @endphp
                        <div class="flex flex-col items-center w-full group">
                            <div class="text-xs text-gray-500 mb-1 opacity-0 group-hover:opacity-100 transition-opacity print:opacity-100">{{ $count }}</div>
                            <div class="w-full bg-primary-100 dark:bg-primary-900/50 rounded-t-md relative group hover:bg-primary-200 transition-colors print:bg-gray-300">
                                <div class="absolute bottom-0 left-0 right-0 bg-primary-500 rounded-t-md transition-all duration-500 print:bg-gray-600" style="height: {{ $height }}%"></div>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-2 font-medium print-text-black">{{ $range }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Estado Civil -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-800 print-break-inside-avoid print:shadow-none print:border print:mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2 print-text-black">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    Estado Civil
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($stats['estado_civil'] ?? [] as $label => $count)
                        <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-transparent dark:border-gray-700 print:border print:bg-white">
                            <div class="text-2xl font-bold text-gray-800 dark:text-white print-text-black">{{ $count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate print-text-black" title="{{ $label }}">{{ $label ?: 'Não informado' }}</div>
                        </div>
                    @endforeach
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

    <!-- Footer Actions -->
    <div class="mt-8 flex justify-end gap-4 no-print fixed bottom-6 right-6">
        <x-filament::button color="gray" outlined x-on:click="$dispatch('close-modal', { id: 'infographic-modal' })">
            Fechar
        </x-filament::button>
        <x-filament::button icon="heroicon-o-printer" onclick="window.print()">
            Imprimir PDF
        </x-filament::button>
    </div>
</div>

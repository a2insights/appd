    <div class="flex items-center gap-4" 
        x-data="{ 
        isGenerating: false,
        progress: 0,
        interval: null,
        statusMessage: 'Iniciando...',
        messages: [
            'Processando filtros...',
            'Calculando estatísticas...',
            'Gerando gráficos...',
            'Renderizando PDF...',
            'Finalizando...'
        ],
        startFakeProgress() {
            this.isGenerating = true;
            this.progress = 0;
            this.statusMessage = this.messages[0];
            
            let stepIndex = 0;
            
            this.interval = setInterval(() => {
                if (this.progress < 95) {
                    // Slower, non-linear progress
                    let step = 1;
                    // Randomize step slightly for realism
                    if (Math.random() > 0.5) step = 2;
                    
                    if (this.progress < 30) step = Math.random() * 2 + 1; // 1-3
                    else if (this.progress > 80) step = 0.2; // Very slow at the end
                    
                    this.progress += step;
                    
                    // Update messages based on progress
                    if (this.progress > 20 && stepIndex < 1) { this.statusMessage = this.messages[1]; stepIndex = 1; }
                    if (this.progress > 40 && stepIndex < 2) { this.statusMessage = this.messages[2]; stepIndex = 2; }
                    if (this.progress > 70 && stepIndex < 3) { this.statusMessage = this.messages[3]; stepIndex = 3; }
                    if (this.progress > 90 && stepIndex < 4) { this.statusMessage = this.messages[4]; stepIndex = 4; }
                }
            }, 800);
        },
        stopFakeProgress() {
            if (this.progress === 100) return;
            this.progress = 100;
            this.statusMessage = 'Download iniciado!';
            clearInterval(this.interval);
            setTimeout(() => { 
                this.isGenerating = false; 
                new FilamentNotification()
                    .title('Download concluído')
                    .success()
                    .body('O arquivo foi gerado com sucesso.')
                    .send();
            }, 1000);
        },
        init() {
            // Hook into Livewire to detect when the request finishes
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    if (this.isGenerating) {
                        this.stopFakeProgress();
                    }
                })
                fail(() => {
                    if (this.isGenerating) {
                        this.isGenerating = false;
                        new FilamentNotification().title('Erro no download').danger().send();
                    }
                })
            })
        }
    }"
    x-on:start-download-progress.window="startFakeProgress()"
    >
        <template x-teleport="body">
            <div x-show="isGenerating" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 flex items-center justify-center bg-gray-900/80 backdrop-blur-sm !z-[99999]" 
                 style="z-index: 99999;">
                <div class="bg-white dark:bg-gray-800 p-10 rounded-2xl shadow-2xl text-center max-w-md w-full mx-4 border border-gray-100 dark:border-gray-700">
                    <div class="mb-12 relative my-4">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold text-primary-600 dark:text-primary-400" x-text="Math.round(progress) + '%'"></span>
                        </div>
                        <svg class="animate-spin h-16 w-16 text-primary-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" x-text="statusMessage"></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Aguarde enquanto preparamos seu arquivo.</p>
                    
                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 overflow-hidden" x-show="progress > 0">
                        <div class="bg-primary-600 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>
            </div>
        </template>
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Total Encontrado: <span class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $count }}</span>
        </div>
        
        @if($count > 0)
            <x-filament::button outlined size="sm" x-on:click="$dispatch('open-modal', { id: 'preview-table-modal' })">
                Ver Lista
            </x-filament::button>

            <x-filament::modal id="infographic-modal" width="screen" sticky-footer slide-over sticky-header>
                <x-slot name="trigger">
                    <x-filament::button color="info" icon="heroicon-o-chart-pie" size="sm">
                        Infográfico
                    </x-filament::button>
                </x-slot>

                <x-slot name="heading">
                    Infográfico da Segmentação
                </x-slot>

                <div class="w-full">
                    @livewire('segmentacao-infographic', ['filters' => $filters], key($this->getInfographicKey()))
                </div>

                <x-slot name="footer">
                    <div class="flex justify-end gap-4 w-full">
                        <x-filament::button color="gray" outlined x-on:click="close()">
                            Fechar
                        </x-filament::button>
                        
                        {{ $this->downloadPdfAction }}
                    </div>
                </x-slot>
            </x-filament::modal>
        @endif
        
        <x-filament::modal id="preview-table-modal" width="5xl">
            <x-slot name="heading">
                Pré-visualização de Associados
            </x-slot>

            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                {{ $this->table }}
            </div>

    <script>
        window.startDownloadProgress = function() {
            window.dispatchEvent(new CustomEvent('start-download-progress'));
        }
    </script>
        </x-filament::modal>
    </div>


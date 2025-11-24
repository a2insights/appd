<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-6">
        {{-- Sidebar de Arquivos --}}
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-code-bracket" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        Documentação Técnica
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Guias técnicos e referências
                    </p>
                </div>

                <div class="p-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach ($this->getTechnicalFiles() as $categoryKey => $files)
                        <div class="mb-3">
                            {{-- Categoria --}}
                            <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <x-filament::icon :icon="$this->getCategoryIcon($categoryKey)" class="w-4 h-4" />
                                {{ $this->getCategoryTitle($categoryKey) }}
                            </div>

                            {{-- Arquivos da Categoria --}}
                            <div class="space-y-1 ml-2">
                                @foreach ($files as $fileKey => $fileTitle)
                                    <button
                                        wire:click="changeFile('{{ $categoryKey }}', '{{ $fileKey }}')"
                                        @class([
                                            'w-full text-left px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-sm',
                                            'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-medium' => $activeCategory === $categoryKey && $activeFile === $fileKey,
                                            'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' => !($activeCategory === $categoryKey && $activeFile === $fileKey),
                                        ])
                                    >
                                        <x-filament::icon icon="heroicon-o-document" class="w-4 h-4" />
                                        <span class="truncate">{{ $fileTitle }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if (empty($this->getTechnicalFiles()))
                        <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Nenhum documento encontrado
                        </div>
                    @endif
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2 mb-2">
                            <x-filament::icon icon="heroicon-o-folder" class="w-4 h-4" />
                            <span class="font-medium">Localização</span>
                        </div>
                        <div class="ml-6 font-mono text-[10px]">docs/technical/*.md</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Área de Conteúdo --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 min-h-[600px]">
                @if (!empty($activeFile))
                    {{-- Header do Arquivo --}}
                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $this->getTechnicalFiles()[$activeCategory][$activeFile] ?? 'Documento' }}
                                </h2>
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4" />
                                    <span class="font-mono">{{ $activeFile }}.md</span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ $this->getCategoryTitle($activeCategory) }}</span>
                                </div>
                            </div>
                            
                            <a
                                href="{{ asset('docs/technical/' . $activeFile . '.md') }}"
                                download
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
                                title="Baixar arquivo"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-down-tray" class="w-4 h-4" />
                                <span class="hidden sm:inline">Download</span>
                            </a>
                        </div>
                    </div>

                    {{-- Conteúdo Markdown --}}
                    <div
                        x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, 50)"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="prose prose-sm sm:prose lg:prose-lg dark:prose-invert max-w-none"
                    >
                        {!! $this->getFileContent($activeFile) !!}
                    </div>
                @else
                    {{-- Estado Vazio --}}
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <x-filament::icon icon="heroicon-o-document-text" class="w-10 h-10 text-gray-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Nenhum arquivo selecionado
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Selecione um arquivo no menu lateral para visualizar
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>

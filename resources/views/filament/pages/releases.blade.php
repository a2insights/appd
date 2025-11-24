<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-6">
        {{-- Sidebar de Releases --}}
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-rocket-launch" class="w-5 h-5 text-green-600 dark:text-green-400" />
                        Releases
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Notas de versão do sistema
                    </p>
                </div>

                <div class="p-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @forelse ($this->getReleaseFiles() as $fileKey => $fileTitle)
                        <button
                            wire:click="changeFile('{{ $fileKey }}')"
                            @class([
                                'w-full text-left px-3 py-2 rounded-lg transition-all duration-200 mb-1',
                                'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 font-medium border border-green-200 dark:border-green-700' => $activeFile === $fileKey,
                                'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' => $activeFile !== $fileKey,
                            ])
                        >
                            <div class="flex items-start gap-2">
                                <x-filament::icon icon="heroicon-o-tag" class="w-4 h-4 mt-0.5 flex-shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate">{{ $fileTitle }}</div>
                                    @if ($date = $this->getFileDate($fileKey))
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $date }}</div>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <x-filament::icon icon="heroicon-o-inbox" class="w-8 h-8 mx-auto mb-2 text-gray-400" />
                            <p>Nenhuma release encontrada</p>
                            <p class="text-xs mt-1">Adicione arquivos .md em docs/releases/</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2 mb-2">
                            <x-filament::icon icon="heroicon-o-folder" class="w-4 h-4" />
                            <span class="font-medium">Localização</span>
                        </div>
                        <div class="ml-6 font-mono text-[10px]">docs/releases/*.md</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Área de Conteúdo --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 min-h-[600px]">
                @if (!empty($activeFile))
                    {{-- Header da Release --}}
                    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-700">
                                        <x-filament::icon icon="heroicon-o-rocket-launch" class="w-3.5 h-3.5" />
                                        Release
                                    </span>
                                    @if ($date = $this->getFileDate($activeFile))
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $date }}</span>
                                    @endif
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $this->getReleaseFiles()[$activeFile] ?? 'Release' }}
                                </h2>
                            </div>
                            
                            <a
                                href="{{ asset('docs/releases/' . $activeFile . '.md') }}"
                                download
                                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors"
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
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-4">
                            <x-filament::icon icon="heroicon-o-rocket-launch" class="w-10 h-10 text-green-600 dark:text-green-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Nenhuma release selecionada
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Selecione uma versão no menu lateral
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>

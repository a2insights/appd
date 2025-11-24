<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-6">
        {{-- Sidebar de Categorias e Seções --}}
        <div class="col-span-12 lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-book-open" class="w-5 h-5" />
                        Navegação
                    </h3>
                </div>

                <div class="p-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                    @foreach ($this->getCategories() as $categoryKey => $category)
                        <div class="mb-3">
                            {{-- Categoria --}}
                            <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <x-filament::icon :icon="$category['icon']" class="w-4 h-4" />
                                {{ $category['title'] }}
                            </div>

                            {{-- Seções da Categoria --}}
                            <div class="space-y-1 ml-2">
                                @foreach ($category['sections'] as $sectionKey => $section)
                                    <button
                                        wire:click="changeSection('{{ $categoryKey }}', '{{ $sectionKey }}')"
                                        @class([
                                            'w-full text-left px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-sm',
                                            'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 font-medium' => $activeCategory === $categoryKey && $activeSection === $sectionKey,
                                            'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' => !($activeCategory === $categoryKey && $activeSection === $sectionKey),
                                        ])
                                    >
                                        <x-filament::icon :icon="$section['icon']" class="w-4 h-4" />
                                        <span>{{ $section['title'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-2 mb-2">
                            <x-filament::icon icon="heroicon-o-information-circle" class="w-4 h-4" />
                            <span class="font-medium">Versão do Sistema</span>
                        </div>
                        <div class="ml-6">v2.0 - Atualizado em {{ now()->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Área de Conteúdo --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 min-h-[600px]">
                {{-- Breadcrumb --}}
                <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-home" class="w-4 h-4" />
                        <span>Documentação</span>
                        <x-filament::icon icon="heroicon-o-chevron-right" class="w-3 h-3" />
                        <span>{{ $this->getCategories()[$activeCategory]['title'] ?? 'Categoria' }}</span>
                        <x-filament::icon icon="heroicon-o-chevron-right" class="w-3 h-3" />
                        <span class="text-gray-900 dark:text-white font-medium">
                            {{ $this->getCategories()[$activeCategory]['sections'][$activeSection]['title'] ?? 'Seção' }}
                        </span>
                    </nav>
                </div>

                {{-- Conteúdo da Seção --}}
                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 50)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="prose dark:prose-invert max-w-none"
                >
                    {!! $this->getSectionContent($activeCategory, $activeSection) !!}
                </div>

                {{-- Navegação Anterior/Próximo --}}
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        {{-- Botão Anterior (implementar lógica) --}}
                    </div>
                    <div>
                        {{-- Botão Próximo (implementar lógica) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

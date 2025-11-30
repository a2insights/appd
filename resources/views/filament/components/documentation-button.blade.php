<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <button
        @click="open = !open"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200"
        title="Documentação e Releases"
    >
        <x-filament::icon
            icon="heroicon-o-book-open"
            class="w-5 h-5"
        />
        <span class="hidden lg:inline">Docs</span>
        <x-filament::icon
            icon="heroicon-o-chevron-down"
            class="w-4 h-4 transition-transform"
            ::class="{ 'rotate-180': open }"
        />
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        style="display: none;"
    >
        <div class="py-1">
            <a
                href="{{ route('filament.admin.pages.documentacao') }}"
                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
                <x-filament::icon icon="heroicon-o-book-open" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                <div>
                    <div class="font-medium">Documentação</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Guias e tutoriais</div>
                </div>
            </a>

            <a
                href="{{ route('filament.admin.pages.releases') }}"
                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
                <x-filament::icon icon="heroicon-o-rocket-launch" class="w-5 h-5 text-green-600 dark:text-green-400" />
                <div>
                    <div class="font-medium">Releases</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Notas de versão</div>
                </div>
            </a>

            <a
                href="{{ route('filament.admin.pages.docs-tecnicas') }}"
                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
                <x-filament::icon icon="heroicon-o-code-bracket" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                <div>
                    <div class="font-medium">Docs Técnicas</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Referências técnicas</div>
                </div>
            </a>
        </div>
    </div>
</div>


<div>
    <h1 class="text-3xl font-bold mb-6">🚀 Primeiros Passos no Sistema APPD</h1>

    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-information-circle"
                class="w-6 h-6 text-blue-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-blue-900 dark:text-blue-100">Bem-vindo!</h3>
                <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                    Este guia vai ajudá-lo a começar a usar o sistema APPD de forma rápida e eficiente.
                </p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">1️⃣ Acesso ao Sistema</h2>
    <p class="mb-4">Para acessar o sistema, você precisa:</p>
    <ul class="space-y-2 mb-6 list-disc list-inside">
        <li>Usuário e senha fornecidos pelo administrador</li>
        <li>Navegador moderno (Chrome, Firefox, Edge ou Safari)</li>
        <li>Conexão com a internet</li>
    </ul>

    <h2 class="text-2xl font-semibold mb-4">2️⃣ Navegação Básica</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-2">Menu Lateral</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Use o menu lateral para navegar entre as diferentes seções do sistema (Associados, Atendimentos, Vagas, etc.)
            </p>
        </div>
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-2">Busca Global</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Use Ctrl+K (ou Cmd+K no Mac) para abrir a busca global e encontrar rapidamente qualquer registro
            </p>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">3️⃣ Principais Funcionalidades</h2>
    <div class="space-y-3 mb-6">
        <button
            wire:click="changeSection('associados', 'cadastro')"
            class="w-full text-left p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        >
            <div class="flex items-center gap-3">
                <x-filament::icon icon="heroicon-o-user-plus" class="w-6 h-6 text-primary-600" />
                <div>
                    <div class="font-medium">Cadastro de Associados</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Aprenda a cadastrar novos associados</div>
                </div>
            </div>
        </button>

        <button
            wire:click="changeSection('associados', 'filtros')"
            class="w-full text-left p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        >
            <div class="flex items-center gap-3">
                <x-filament::icon icon="heroicon-o-funnel" class="w-6 h-6 text-primary-600" />
                <div>
                    <div class="font-medium">Filtros de Associados</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">60+ opções para encontrar associados</div>
                </div>
            </div>
        </button>

        <button
            wire:click="changeSection('atendimentos', 'criar-atendimento')"
            class="w-full text-left p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        >
            <div class="flex items-center gap-3">
                <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 text-primary-600" />
                <div>
                    <div class="font-medium">Registrar Atendimentos</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Como criar e gerenciar atendimentos</div>
                </div>
            </div>
        </button>
    </div>

    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-light-bulb"
                class="w-6 h-6 text-yellow-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-yellow-900 dark:text-yellow-100">Dica</h3>
                <p class="text-sm text-yellow-800 dark:text-yellow-200 mt-1">
                    Explore o menu de navegação à esquerda para conhecer todas as funcionalidades do sistema!
                </p>
            </div>
        </div>
    </div>
</div>

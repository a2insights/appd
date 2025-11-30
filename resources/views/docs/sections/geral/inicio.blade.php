<div>
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl mb-6 shadow-lg">
            <x-filament::icon icon="heroicon-o-book-open" class="w-12 h-12 text-white" />
        </div>
        
        <h1 class="text-4xl font-bold mb-4">Bem-vindo à Central de Documentação</h1>
        
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Encontre tudo que você precisa para usar o sistema APPD de forma eficiente e produtiva.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <button
            wire:click="changeSection('geral', 'primeiros-passos')"
            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-rocket-launch" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">Primeiros Passos</h3>
            </div>
            <p class="text-sm text-blue-800 dark:text-blue-200">
                Comece aqui se você é novo no sistema
            </p>
        </button>

        <button
            wire:click="changeSection('associados', 'filtros')"
            class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-funnel" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100">Filtros de Associados</h3>
            </div>
            <p class="text-sm text-purple-800 dark:text-purple-200">
                Aprenda a usar os 27 filtros disponíveis
            </p>
        </button>

        <button
            wire:click="changeSection('atendimentos', 'criar-atendimento')"
            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100">Atendimentos</h3>
            </div>
            <p class="text-sm text-green-800 dark:text-green-200">
                Como registrar e gerenciar atendimentos
            </p>
        </button>

        <button
            wire:click="changeSection('vagas', 'cadastro-vagas')"
            class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border border-orange-200 dark:border-orange-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-briefcase" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100">Vagas e Talentos</h3>
            </div>
            <p class="text-sm text-orange-800 dark:text-orange-200">
                Gestão de vagas e encaminhamentos
            </p>
        </button>

        <button
            wire:click="changeSection('relatorios', 'dashboard')"
            class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 border border-pink-200 dark:border-pink-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-pink-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-chart-pie" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-pink-900 dark:text-pink-100">Relatórios</h3>
            </div>
            <p class="text-sm text-pink-800 dark:text-pink-200">
                Dashboards e exportação de dados
            </p>
        </button>

        <button
            wire:click="changeSection('ajuda', 'faq')"
            class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 text-left hover:shadow-lg transition-all duration-200 hover:-translate-y-1"
        >
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-question-mark-circle" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">Ajuda e Suporte</h3>
            </div>
            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                FAQ e solução de problemas
            </p>
        </button>
    </div>

    <div class="bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/20 border border-primary-200 dark:border-primary-700 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <x-filament::icon icon="heroicon-o-light-bulb" class="w-6 h-6 text-white" />
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary-900 dark:text-primary-100 mb-2">
                    Dica: Use a Busca
                </h3>
                <p class="text-sm text-primary-800 dark:text-primary-200">
                    Não encontrou o que procura? Use o menu de navegação à esquerda para explorar todas as seções disponíveis, ou entre em contato com o suporte técnico.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">27</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Filtros de Associados</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">60+</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Faixas de Idade</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">8</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Categorias de Documentação</div>
        </div>
    </div>
</div>

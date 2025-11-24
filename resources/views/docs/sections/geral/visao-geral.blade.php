<div>
    <h1 class="text-3xl font-bold mb-6">📊 Visão Geral do Sistema APPD</h1>

    <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
        O Sistema APPD é uma plataforma completa para gestão de associados, atendimentos, vagas e talentos.
    </p>

    <h2 class="text-2xl font-semibold mb-4">🎯 Principais Módulos</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-users" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Associados</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Gestão completa de associados com cadastro, filtros avançados, carteirinhas e benefícios.
            </p>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>27 filtros disponíveis</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>60+ faixas de idade</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Gestão de carteirinhas</span>
                </li>
            </ul>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-clipboard-document-list" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Atendimentos</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Registro e acompanhamento de atendimentos realizados aos associados e público em geral.
            </p>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Múltiplos tipos de atendimento</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Geração de declarações</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Histórico completo</span>
                </li>
            </ul>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-briefcase" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Vagas e Talentos</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Gestão de vagas de emprego e banco de talentos para encaminhamentos.
            </p>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Cadastro de vagas</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Banco de talentos</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Encaminhamentos</span>
                </li>
            </ul>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-chart-pie" class="w-6 h-6 text-white" />
                </div>
                <h3 class="text-lg font-semibold">Relatórios</h3>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Dashboards e relatórios para acompanhamento de métricas e indicadores.
            </p>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Dashboard interativo</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Exportação de dados</span>
                </li>
                <li class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4 text-green-500" />
                    <span>Métricas em tempo real</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-700 rounded-lg p-6">
        <h3 class="font-semibold mb-3 flex items-center gap-2">
            <x-filament::icon icon="heroicon-o-sparkles" class="w-5 h-5 text-primary-600" />
            Recursos Avançados
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 text-primary-600" />
                <span>Busca global (Ctrl+K)</span>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 text-primary-600" />
                <span>Dark mode</span>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 text-primary-600" />
                <span>Responsivo (mobile-first)</span>
            </div>
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 text-primary-600" />
                <span>Histórico de alterações</span>
            </div>
        </div>
    </div>
</div>

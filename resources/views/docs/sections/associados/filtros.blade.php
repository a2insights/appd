<div>
    <h1 class="text-3xl font-bold mb-6">📊 Visão Geral dos Filtros de Associados</h1>

    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-information-circle"
                class="w-6 h-6 text-blue-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-blue-900 dark:text-blue-100">Sistema de Filtros v2.0</h3>
                <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                    Sistema completo de filtros para explorar e segmentar a base de associados com mais de 60 opções de faixas etárias e múltiplos critérios de busca.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-6 border border-primary-200 dark:border-primary-700">
            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">27</div>
            <div class="text-sm font-medium text-primary-900 dark:text-primary-100">Filtros Totais</div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-6 border border-green-200 dark:border-green-700">
            <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">60+</div>
            <div class="text-sm font-medium text-green-900 dark:text-green-100">Faixas de Idade</div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-6 border border-purple-200 dark:border-purple-700">
            <div class="text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">8</div>
            <div class="text-sm font-medium text-purple-900 dark:text-purple-100">Categorias</div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-6 border border-orange-200 dark:border-orange-700">
            <div class="text-4xl font-bold text-orange-600 dark:text-orange-400 mb-2">15</div>
            <div class="text-sm font-medium text-orange-900 dark:text-orange-100">Com Busca Integrada</div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">📂 Categorias de Filtros</h2>

    <div class="space-y-3 mb-8">
        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">🆔</span>
            <div>
                <div class="font-medium">Identificação</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Status, Sexo, Declaração Sexual, Estado Civil</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">📅</span>
            <div>
                <div class="font-medium">Datas</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Cadastro, Nascimento, Renovação de Carteirinha</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">🎂</span>
            <div>
                <div class="font-medium">Idade</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Rápida (60+ opções), Personalizada, Aniversariantes</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">📍</span>
            <div>
                <div class="font-medium">Localização</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">UF, Cidade, Perímetro</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">👥</span>
            <div>
                <div class="font-medium">Sociodemográficos</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Religião, Escolaridade, Raça, Ocupação</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">♿</span>
            <div>
                <div class="font-medium">Deficiência</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Tipo, Causa, Aparelhos, CID-10, CRM</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">🔗</span>
            <div>
                <div class="font-medium">Relacionamentos</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Benefícios, Carteirinha, Talento</div>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <span class="text-2xl">📞</span>
            <div>
                <div class="font-medium">Contato</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">WhatsApp, E-mail, Foto</div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">🚀 Novidades da Versão 2.0</h2>

    <ul class="space-y-2 mb-6">
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>60+ faixas de idade</strong> com intervalos de 4, 5, 8 e 10 anos</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Busca integrada</strong> em 15 filtros para encontrar opções rapidamente</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Filtros ternários</strong> (Sim/Não/Todos) para campos booleanos</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Filtro personalizado de idade</strong> com múltiplos intervalos customizados</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Performance otimizada</strong> com preload e cache</span>
        </li>
    </ul>

    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-light-bulb"
                class="w-6 h-6 text-yellow-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-yellow-900 dark:text-yellow-100">Dica</h3>
                <p class="text-sm text-yellow-800 dark:text-yellow-200 mt-1">
                    Use a navegação à esquerda para explorar cada categoria de filtros e aprender como usá-los efetivamente.
                </p>
            </div>
        </div>
    </div>
</div>

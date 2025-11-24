<div>
    <h1 class="text-3xl font-bold mb-6">🎂 Filtros de Idade</h1>

    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-sparkles"
                class="w-6 h-6 text-green-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-green-900 dark:text-green-100">Novidade v2.0</h3>
                <p class="text-sm text-green-800 dark:text-green-200 mt-1">
                    Agora com <strong>60+ opções de faixas etárias</strong> com intervalos de 4, 5, 8 e 10 anos para máxima flexibilidade!
                </p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">📊 Faixa de Idade (Rápida)</h2>

    <p class="mb-4">
        O filtro de <strong>Faixa de Idade (Rápida)</strong> oferece mais de 60 opções pré-definidas organizadas por intervalos diferentes:
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm">Intervalos de 4 anos</span>
            </h3>
            <div class="space-y-1 text-sm">
                <div>• 0-3 anos</div>
                <div>• 4-7 anos</div>
                <div>• 8-11 anos</div>
                <div>• 12-15 anos</div>
                <div>• 16-19 anos</div>
                <div>• 20-23 anos</div>
                <div>• ... até 56-59 anos</div>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded text-sm">Intervalos de 5 anos</span>
            </h3>
            <div class="space-y-1 text-sm">
                <div>• 0-4 anos</div>
                <div>• 5-9 anos</div>
                <div>• 10-14 anos</div>
                <div>• 15-19 anos</div>
                <div>• 20-24 anos</div>
                <div>• 25-29 anos</div>
                <div>• ... até 55-59 anos</div>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-sm">Intervalos de 8 anos</span>
            </h3>
            <div class="space-y-1 text-sm">
                <div>• 0-7 anos</div>
                <div>• 8-15 anos</div>
                <div>• 16-23 anos</div>
                <div>• 24-31 anos</div>
                <div>• 32-39 anos</div>
                <div>• 40-47 anos</div>
                <div>• ... até 56-63 anos</div>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <h3 class="font-semibold mb-3 flex items-center gap-2">
                <span class="bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 px-2 py-1 rounded text-sm">Intervalos de 10 anos</span>
            </h3>
            <div class="space-y-1 text-sm">
                <div>• 0-9 anos</div>
                <div>• 10-19 anos</div>
                <div>• 20-29 anos</div>
                <div>• 30-39 anos</div>
                <div>• 40-49 anos</div>
                <div>• 50-59 anos</div>
                <div>• ... até 80-89 anos</div>
            </div>
        </div>
    </div>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-2 flex items-center gap-2">
            <x-filament::icon icon="heroicon-o-magnifying-glass" class="w-5 h-5 text-blue-600" />
            Busca Integrada
        </h3>
        <p class="text-sm">
            O filtro possui <strong>busca integrada</strong>! Digite a idade que procura (ex: "3", "15", "60") para encontrar rapidamente as faixas disponíveis.
        </p>
    </div>

    <h2 class="text-2xl font-semibold mb-4 mt-8">⚙️ Faixa de Idade (Personalizada)</h2>

    <p class="mb-4">
        Para necessidades específicas, use o filtro <strong>Faixa de Idade (Personalizada)</strong> que permite:
    </p>

    <ul class="space-y-2 mb-6">
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Intervalos customizados</strong> - Defina qualquer faixa (ex: 3-5 anos, 27-33 anos)</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Múltiplos intervalos</strong> - Combine várias faixas (ex: 18-25 OU 60+)</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Validação automática</strong> - Garante que idade máxima ≥ idade mínima</span>
        </li>
        <li class="flex items-start gap-2">
            <x-filament::icon icon="heroicon-o-check-circle" class="w-5 h-5 text-green-500 mt-0.5" />
            <span><strong>Labels dinâmicos</strong> - Mostra os intervalos ativos nos chips de filtro</span>
        </li>
    </ul>

    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 mb-6">
        <h3 class="font-semibold mb-3">💡 Exemplo de Uso</h3>
        <div class="space-y-3">
            <div>
                <div class="text-sm font-medium mb-1">Cenário: Filtrar crianças de 3 a 5 anos</div>
                <div class="bg-white dark:bg-gray-900 rounded p-3 text-sm">
                    <div class="font-mono">Intervalo 1: Min: <span class="text-primary-600">3</span> | Max: <span class="text-primary-600">5</span></div>
                </div>
            </div>

            <div>
                <div class="text-sm font-medium mb-1">Cenário: Filtrar jovens (18-25) OU idosos (60+)</div>
                <div class="bg-white dark:bg-gray-900 rounded p-3 text-sm space-y-1">
                    <div class="font-mono">Intervalo 1: Min: <span class="text-primary-600">18</span> | Max: <span class="text-primary-600">25</span></div>
                    <div class="font-mono">Intervalo 2: Min: <span class="text-primary-600">60</span> | Max: <span class="text-gray-400">(vazio)</span></div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4 mt-8">🎉 Aniversariantes do Mês</h2>

    <p class="mb-4">
        Filtre associados por mês de nascimento para:
    </p>

    <ul class="space-y-2 mb-6">
        <li class="flex items-start gap-2">
            <span class="text-primary-600">•</span>
            <span>Enviar mensagens de parabéns</span>
        </li>
        <li class="flex items-start gap-2">
            <span class="text-primary-600">•</span>
            <span>Planejar eventos e comemorações</span>
        </li>
        <li class="flex items-start gap-2">
            <span class="text-primary-600">•</span>
            <span>Gerar relatórios mensais</span>
        </li>
    </ul>

    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4">
        <div class="flex items-start">
            <x-filament::icon
                icon="heroicon-o-light-bulb"
                class="w-6 h-6 text-yellow-500 mr-3 mt-0.5"
            />
            <div>
                <h3 class="font-semibold text-yellow-900 dark:text-yellow-100">Dica Pro</h3>
                <p class="text-sm text-yellow-800 dark:text-yellow-200 mt-1">
                    Combine o filtro de <strong>Aniversariantes do Mês</strong> com <strong>Possui WhatsApp?</strong> para criar listas de envio de mensagens de parabéns automatizadas!
                </p>
            </div>
        </div>
    </div>
</div>

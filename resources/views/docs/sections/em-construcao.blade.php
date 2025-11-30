<div class="text-center py-12">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 dark:bg-yellow-900/20 rounded-full mb-4">
        <x-filament::icon icon="heroicon-o-wrench-screwdriver" class="w-10 h-10 text-yellow-600 dark:text-yellow-400" />
    </div>
    
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
        Seção em Construção
    </h2>
    
    <p class="text-gray-600 dark:text-gray-400 mb-6">
        Esta seção da documentação está sendo preparada.
    </p>
    
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4 max-w-md mx-auto">
        <div class="flex items-start gap-3">
            <x-filament::icon icon="heroicon-o-information-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" />
            <div class="text-left text-sm">
                <p class="font-medium text-blue-900 dark:text-blue-100 mb-1">Informações da Seção</p>
                <p class="text-blue-800 dark:text-blue-200">
                    <strong>Categoria:</strong> {{ ucfirst($category) }}<br>
                    <strong>Seção:</strong> {{ ucfirst(str_replace('-', ' ', $section)) }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-8">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Enquanto isso, explore outras seções disponíveis no menu lateral.
        </p>
    </div>
</div>

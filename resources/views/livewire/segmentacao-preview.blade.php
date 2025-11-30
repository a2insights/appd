<div>
    <div class="space-y-4">
        @if($readyToLoad)
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Encontrado</h3>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $count }}</p>
                        @if($count > 0)
                            <x-filament::button
                                outlined
                                x-on:click="$dispatch('open-modal', { id: 'preview-table-modal' })"
                            >
                                Ver Lista
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            </div>

            <x-filament::modal id="preview-table-modal" width="5xl">
                <x-slot name="heading">
                    Pré-visualização de Associados
                </x-slot>

                <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                    {{ $this->table }}
                </div>
            </x-filament::modal>

            @if($count === 0)
                <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow dark:bg-gray-800 dark:text-gray-400">
                    Nenhum associado encontrado com os filtros selecionados.
                </div>
            @endif
        @else
            <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow dark:bg-gray-800 dark:text-gray-400">
                Clique em "Atualizar Pré-visualização" para ver os resultados.
            </div>
        @endif
    </div>
</div>

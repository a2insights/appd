<div>
    <div class="flex items-center gap-4">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Total Encontrado: <span class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ $count }}</span>
        </div>
        
        @if($count > 0)
            <x-filament::button outlined size="sm" x-on:click="$dispatch('open-modal', { id: 'preview-table-modal' })">
                Ver Lista
            </x-filament::button>
        @endif
        
        <x-filament::modal id="preview-table-modal" width="5xl">
            <x-slot name="heading">
                Pré-visualização de Associados
            </x-slot>

            <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
                {{ $this->table }}
            </div>
        </x-filament::modal>
    </div>
</div>

<div class="fixed bottom-0 left-0 right-0 z-20 p-4 bg-white border-t border-gray-200 shadow-lg dark:bg-gray-900 dark:border-gray-700" x-data>
    <div class="flex items-center justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <livewire:segmentacao-preview />
        
        <x-filament::button 
            type="button"
            x-on:click="
                $dispatch('update-segmentacao-preview', { 
                    filters: $wire.get('data.filters') 
                })
            "
            icon="heroicon-o-arrow-path"
        >
            Atualizar Filtros
        </x-filament::button>
    </div>
</div>

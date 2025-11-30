<div 
    class="fixed bottom-0 left-0 right-0 z-10 p-4 bg-white border-t border-gray-200 shadow-lg dark:bg-gray-900 dark:border-gray-700" 
    x-data="{ 
        loading: false,
        lastPreviewed: @js($initialFilters),
        currentFilters: @entangle('data.filters').live,
        get isDirty() {
            return JSON.stringify(this.currentFilters) !== JSON.stringify(this.lastPreviewed);
        },
        updatePreview() {
            this.loading = true;
            this.lastPreviewed = JSON.parse(JSON.stringify(this.currentFilters));
            $dispatch('update-segmentacao-preview', { 
                filters: this.currentFilters 
            });
        }
    }"
    x-on:preview-updated.window="loading = false"
>
    <div class="flex items-center justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <livewire:segmentacao-preview :initialFilters="$initialFilters" />
        
        <x-filament::button 
            type="button"
            x-on:click="updatePreview()"
            x-bind:disabled="loading || !isDirty"
            x-bind:class="{ 'opacity-50 cursor-not-allowed': loading || !isDirty }"
            x-bind:outlined="!isDirty"
            icon="heroicon-o-eye"
        >
            <div class="flex items-center gap-2">
                <x-filament::loading-indicator class="h-5 w-5" x-show="loading" />
                <span>Pré-visualizar</span>
            </div>
        </x-filament::button>
    </div>
</div>

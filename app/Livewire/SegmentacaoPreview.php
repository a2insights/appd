<?php

namespace App\Livewire;

use App\Models\Associado;
use App\Filament\Filters\AssociadoFiltersTable;
use Livewire\Attributes\On;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Filament\Resources\AssociadoResource;
use Livewire\Component;

class SegmentacaoPreview extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $filters = [];
    public $count = 0;
    public $readyToLoad = false;

    #[On('update-preview')]
    public function update($filters)
    {
        $this->filters = $filters;
        $this->readyToLoad = true;
        // Reset table pagination when filters change
        $this->resetTable(); 
    }

    public function table(Table $table): Table
    {
        // Reuse columns from AssociadoResource
        // We need to instantiate a dummy table to extract columns or manually define them if extraction is too complex due to context.
        // Given AssociadoResource::table() adds actions and filters we might not want, let's try to extract just columns if possible,
        // or safer: define the key columns here to ensure a lightweight preview.
        // However, the user asked to "aproveitar o codigo".
        // Let's try to call AssociadoResource::table($table) but we need to remove actions/filters if we want a clean preview.
        
        // A better approach for "preview" is usually a simplified view. 
        // But if we want to reuse the EXACT table definition:
        
        $table = AssociadoResource::table($table);

        // We might want to disable actions for the preview to avoid side effects
        return $table
            ->query(function () {
                $query = Associado::query();
                $this->applyFilters($query);
                return $query;
            })
            ->actions([]) // Clear actions for preview
            ->bulkActions([]) // Clear bulk actions
            ->filters([]) // Clear filters as we apply them manually from the form state
            ->headerActions([]); // Clear header actions
    }

    protected function applyFilters($query)
    {
        if (!$this->readyToLoad) {
            // Return empty result if not ready
            $query->whereRaw('1 = 0');
            return;
        }

        $definitions = AssociadoFiltersTable::filters();

        foreach ($definitions as $filter) {
            $name = $filter->getName();
            $value = data_get($this->filters, $name);

            if (empty($value)) {
                continue;
            }

            $filterData = $this->filters;
            $filterData['value'] = $value;

            $filter->apply($query, $filterData);
        }
        
        // Update count based on the current query
        // Note: This might run twice (once for count, once for table), but it ensures sync.
        // Ideally we capture the count from the table query, but for now this is explicit.
        $this->count = $query->count();
    }

    public function render(): View
    {
        return view('livewire.segmentacao-preview');
    }
}

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

    public function mount($initialFilters = [])
    {
        if (!empty($initialFilters)) {
            $this->filters = $initialFilters;
            $this->readyToLoad = true;
            
            $query = Associado::query();
            $this->applyFilters($query);
            $this->count = $query->count();
        }
    }

    #[On('update-segmentacao-preview')]
    public function update($filters)
    {
        $this->filters = $filters;
        $this->readyToLoad = true;
        
        $this->resetTable();
        $this->dispatch('preview-updated');
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
            ->headerActions([]) // Clear header actions
            ->defaultPaginationPageOption(5);
    }

    protected function applyFilters($query)
    {
        if (!$this->readyToLoad) {
            // Return empty result if not ready
            $query->whereRaw('1 = 0');
            return;
        }

        $definitions = AssociadoFiltersTable::filters();
        
        // // Create a dummy table instance to satisfy the filter's dependency on a Table object.
        // // We use an anonymous component to avoid recursion and side effects from the main component's table() method.
        // $dummyComponent = new class extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable {
        //     use \Filament\Forms\Concerns\InteractsWithForms;
        //     use \Filament\Tables\Concerns\InteractsWithTable;
            
        //     public function table(\Filament\Tables\Table $table): \Filament\Tables\Table {
        //         return $table->query(\App\Models\Associado::query());
        //     }
            
        //     public function bootDummy() {
        //         // Manually initialize the table to satisfy the typed property requirement
        //         $this->table = $this->makeTable();
        //     }
        // };
        
        // $dummyComponent->bootDummy();
        // $dummyTable = $dummyComponent->getTable();

        foreach ($definitions as $filter) {
            // Inject the dummy table into the filter instance
            if (method_exists($filter, 'table')) {
               // $filter->table($dummyTable);
            }
            
            $name = $filter->getName();
            $value = data_get($this->filters, $name);

            if ($filter instanceof SelectFilter || $filter instanceof \Filament\Tables\Filters\TernaryFilter) {
                if (!blank($value) && $value !== '' && $value !== 'all') {
                    if ($filter instanceof \Filament\Tables\Filters\TernaryFilter) {
                         if ($value == 1) $value = true;
                         if ($value == 0) $value = false;
                         
                         $filter->apply($query, ['value' => $value]);
                    } elseif ($filter instanceof SelectFilter) {
                        $filter->apply($query, ['value' => $value, 'values' => $value]);
                    }
                }
                continue;
            }

            $filterData = $this->filters;
            if (!blank($value)) {
                $filterData['value'] = $value;
            }

            $filter->apply($query, $filterData);
        }
        
        $this->count = $query->count();
    }

    public function render(): View
    {
        return view('livewire.segmentacao-preview');
    }
}

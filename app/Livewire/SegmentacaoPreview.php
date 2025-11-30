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
        $definitions = AssociadoFiltersTable::filters();

        foreach ($definitions as $filter) {
            $name = $filter->getName();
            $value = data_get($this->filters, $name);

            // Skip empty values (but allow '0' for boolean-like fields if needed, though blank() handles it)
            // blank() returns true for null, '', [], but false for 0, '0', false.
            if (blank($value) || $value === 'all') {
                continue;
            }

            // 1. Handle SelectFilter
            if ($filter instanceof SelectFilter) {
                $values = (array) $value;
                
                // Handle Relationship Filters manually
                if (in_array($name, ['cid10', 'beneficios'])) {
                    $query->whereHas($name, function ($q) use ($values) {
                        $q->whereIn($q->getModel()->getKeyName(), $values);
                    });
                } else {
                    // Standard attribute filter
                    $query->whereIn($name, $values);
                }
                continue;
            }

            // 2. Handle DateRangeFilter
            if ($filter instanceof \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter) {
                 $dates = explode(' - ', $value);
                 if (count($dates) === 2) {
                     try {
                         $start = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                         $end = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                         
                         if ($name === 'carteirinhas.data_emissao') {
                             $query->whereHas('carteirinhas', function ($q) use ($start, $end) {
                                 $q->whereBetween('data_emissao', [$start, $end]);
                             })->has('carteirinhas', '>', 1);
                         } else {
                             $query->whereBetween($name, [$start, $end]);
                         }
                     } catch (\Exception $e) {
                         // Ignore invalid dates
                     }
                 }
                 continue;
            }

            // 3. Handle Custom Filters (Filter::make)
            // We pass the entire filters array as data because custom filters might depend on multiple fields
            // (e.g. naturalidade depends on naturalidade_uf and naturalidade_municipio_ibge)
            if ($filter instanceof \Filament\Tables\Filters\Filter) {
                // We try to apply it. If it fails due to missing Table, we might need another strategy,
                // but usually custom query callbacks don't rely on the Table instance.
                $filter->apply($query, $this->filters);
            }
        }
        
        $this->count = $query->count();
    }

    public function render(): View
    {
        return view('livewire.segmentacao-preview');
    }

    public function getInfographicKey()
    {
        // Sort filters by key to ensure deterministic order
        $filters = $this->filters;
        ksort($filters);
        return 'infographic-' . md5(json_encode($filters));
    }
}

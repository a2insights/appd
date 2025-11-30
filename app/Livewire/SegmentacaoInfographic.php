<?php

namespace App\Livewire;

use App\Models\Associado;
use App\Filament\Filters\AssociadoFiltersTable;
use Livewire\Component;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class SegmentacaoInfographic extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    public $filters = [];
    public $readyToLoad = false;

    public function mount($filters = [])
    {
        $this->filters = $filters;
        $this->readyToLoad = true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Associado::query())
            ->columns([]);
    }

    public function render()
    {
        $query = Associado::query();
        
        // Apply filters
        $filterDefinitions = AssociadoFiltersTable::filters();
        foreach ($filterDefinitions as $filter) {
            $name = $filter->getName();
            $value = data_get($this->filters, $name);

            // Skip empty values (but allow '0' for boolean-like fields if needed, though blank() handles it)
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
            if ($filter instanceof \Filament\Tables\Filters\Filter) {
                $filter->apply($query, $this->filters);
            }
        }

        // Get stats using Service
        $stats = app(\App\Services\InfographicService::class)->getStats($query);

        return view('livewire.segmentacao-infographic-modal', [
            'stats' => $stats,
            'activeFilters' => $this->getFormattedFilters()
        ]);
    }

    protected function getFormattedFilters()
    {
        return app(\App\Services\FilterFormatterService::class)->format($this->filters);
    }


}

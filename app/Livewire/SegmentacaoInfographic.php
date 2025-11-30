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
        $stats = [];
        
        if ($this->readyToLoad) {
            $query = Associado::query();
            $this->applyFilters($query);
            
            // Clone query for different stats to avoid interference
            $baseQuery = $query->clone();

            $stats['total'] = $baseQuery->count();

            // Sexo
            $stats['sexo'] = $baseQuery->clone()
                ->select('sexo', DB::raw('count(*) as count'))
                ->groupBy('sexo')
                ->pluck('count', 'sexo')
                ->toArray();

            // Estado Civil
            $stats['estado_civil'] = $baseQuery->clone()
                ->select('estado_civil', DB::raw('count(*) as count'))
                ->groupBy('estado_civil')
                ->pluck('count', 'estado_civil')
                ->toArray();

            // Faixa Etária (Age Groups) - DB Agnostic approach
            $stats['faixa_etaria'] = [
                '0-17' => $baseQuery->clone()->whereDate('data_nascimento', '>', now()->subYears(18))->count(),
                '18-29' => $baseQuery->clone()->whereDate('data_nascimento', '<=', now()->subYears(18))->whereDate('data_nascimento', '>', now()->subYears(30))->count(),
                '30-49' => $baseQuery->clone()->whereDate('data_nascimento', '<=', now()->subYears(30))->whereDate('data_nascimento', '>', now()->subYears(50))->count(),
                '50-64' => $baseQuery->clone()->whereDate('data_nascimento', '<=', now()->subYears(50))->whereDate('data_nascimento', '>', now()->subYears(65))->count(),
                '65+' => $baseQuery->clone()->whereDate('data_nascimento', '<=', now()->subYears(65))->count(),
            ];

            // UF (Estado) - Address state
            $stats['estado'] = $baseQuery->clone()
                ->select('estado', DB::raw('count(*) as count'))
                ->whereNotNull('estado')
                ->groupBy('estado')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'estado')
                ->toArray();
                
            // Deficiência (Sim/Não based on having entries in tipo_deficiencia or similar?)
            // Or just group by tipo_deficiencia
            $stats['tipo_deficiencia'] = $baseQuery->clone()
                ->select('tipo_deficiencia', DB::raw('count(*) as count'))
                ->whereNotNull('tipo_deficiencia')
                ->groupBy('tipo_deficiencia')
                ->pluck('count', 'tipo_deficiencia')
                ->toArray();
        }

        return view('livewire.segmentacao-infographic-modal', [
            'stats' => $stats
        ]);
    }

    protected function applyFilters($query)
    {
        // Copied logic from SegmentacaoPreview to ensure consistency
        $definitions = AssociadoFiltersTable::filters();

        // Create a dummy table instance to satisfy the filter's dependency on a Table object.
        // Create a dummy table instance to satisfy the filter's dependency on a Table object.
        // We use an anonymous component to avoid side effects and ensure a valid query is set.
        $dummyComponent = new class extends \Livewire\Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable {
            use \Filament\Forms\Concerns\InteractsWithForms;
            use \Filament\Tables\Concerns\InteractsWithTable;
            
            public function table(\Filament\Tables\Table $table): \Filament\Tables\Table {
                return $table->query(\App\Models\Associado::query());
            }

            public function bootDummy() {
                // Manually initialize the table to satisfy the typed property requirement
                $this->table = $this->makeTable();
            }
        };
        
        $dummyComponent->bootDummy();
        $dummyTable = $dummyComponent->getTable();

        foreach ($definitions as $filter) {
            // Inject the dummy table into the filter instance
            if (method_exists($filter, 'table')) {
                $filter->table($dummyTable);
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

            $filterData = is_array($value) ? $value : [];
            if (!is_array($value) && !blank($value)) {
                $filterData['value'] = $value;
            }

            $filter->apply($query, $filterData);
        }
    }
}

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
        $definitions = AssociadoFiltersTable::filters();

        foreach ($definitions as $filter) {
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
    }
}

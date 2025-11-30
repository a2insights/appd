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
            $filter->apply(
                $query,
                $this->filters,
                $filter
            );
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
        $definitions = AssociadoFiltersTable::filters();
        $formatted = [];

        foreach ($definitions as $filter) {
            $name = $filter->getName();
            $value = data_get($this->filters, $name);

            if (blank($value) || $value === 'all') {
                continue;
            }

            $label = $filter->getLabel();
            $displayValue = $value;

            // Handle Custom 'naturalidade' filter
            if ($name === 'naturalidade') {
                $parts = [];
                if (!empty($value['naturalidade_uf'])) {
                    $parts[] = 'UF: ' . implode(', ', (array)$value['naturalidade_uf']);
                }
                if (!empty($value['naturalidade_municipio_ibge'])) {
                    $municipios = (new \App\Services\MunicipioService())->all();
                    $names = collect((array)$value['naturalidade_municipio_ibge'])
                        ->map(function($ibge) use ($municipios) {
                            $mun = $municipios->firstWhere('codigoIbge', $ibge);
                            return $mun ? $mun->nome : $ibge;
                        })
                        ->join(', ');
                    $parts[] = 'Municípios: ' . $names;
                }
                if (!empty($parts)) {
                    $formatted[$name] = [
                        'label' => $label,
                        'value' => implode(' | ', $parts)
                    ];
                }
                continue;
            }

            // List of boolean/ternary filters that use ToggleButtons
            $booleanFilters = [
                'possui_deficiencia', 
                'possui_crm', 
                'possui_carteirinha', 
                'possui_talento', 
                'possui_whatsapp', 
                'possui_email', 
                'possui_foto'
            ];

            if (in_array($name, $booleanFilters)) {
                if ($value === 'all') {
                    $displayValue = 'Todos';
                } elseif ($value === '1' || $value === 1 || $value === true) {
                    $displayValue = 'Sim';
                } elseif ($value === '0' || $value === 0 || $value === false) {
                    $displayValue = 'Não';
                }
            } elseif ($value === 'all') {
                // Skip other filters if they are 'all', unless we want to show them too.
                // For now, let's skip them to avoid clutter, as usually 'all' is default.
                continue;
            }

            if ($filter instanceof SelectFilter) {
                $options = $filter->getOptions();
                
                // Handle Enums
                if (is_string($options) && enum_exists($options)) {
                   $values = (array) $value;
                   $displayValue = collect($values)
                        ->map(fn($v) => $options::tryFrom($v)?->getLabel() ?? $v)
                        ->join(', ');
                } 
                // Handle Relationships
                elseif ($name === 'cid10') {
                     $displayValue = \App\Models\Cid10::whereIn('id', (array)$value)->pluck('codigo')->join(', ');
                } elseif ($name === 'beneficios') {
                     $displayValue = \App\Models\Beneficio::whereIn('id', (array)$value)->pluck('nome')->join(', ');
                } elseif ($name === 'grupo_segmentacao_id') {
                     $displayValue = \App\Models\GrupoSegmentacao::whereIn('id', (array)$value)->pluck('name')->join(', ');
                }
                // Handle Array Options
                elseif (is_array($options)) {
                    $values = (array) $value;
                    $displayValue = collect($values)
                        ->map(fn($v) => $options[$v] ?? $v)
                        ->join(', ');
                }
            }
            
            // Handle Ternary/Boolean (if not already handled by booleanFilters list above)
            if ($filter instanceof \Filament\Tables\Filters\TernaryFilter && !in_array($name, $booleanFilters)) {
                 $displayValue = $value ? 'Sim' : 'Não';
            }

            $formatted[$name] = [
                'label' => $label,
                'value' => is_array($displayValue) ? implode(', ', $displayValue) : $displayValue
            ];
        }
        
        return $formatted;
    }


}

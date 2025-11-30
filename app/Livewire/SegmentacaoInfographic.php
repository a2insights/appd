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
            $sexoStats = $baseQuery->clone()
                ->select('sexo', DB::raw('count(*) as count'))
                ->groupBy('sexo')
                ->pluck('count', 'sexo')
                ->toArray();
            
            $stats['sexo'] = [];
            foreach ($sexoStats as $key => $value) {
                $label = $key ?: 'Não Informado';
                if (isset($stats['sexo'][$label])) {
                    $stats['sexo'][$label] += $value;
                } else {
                    $stats['sexo'][$label] = $value;
                }
            }

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

            // Naturalidade UF
            $stats['naturalidade_uf'] = $baseQuery->clone()
                ->select('naturalidade_uf', DB::raw('count(*) as count'))
                ->whereNotNull('naturalidade_uf')
                ->groupBy('naturalidade_uf')
                ->orderBy('count', 'desc')
                ->pluck('count', 'naturalidade_uf')
                ->toArray();

            // Naturalidade Município (Top 10)
            $natMunStats = $baseQuery->clone()
                ->select('naturalidade_municipio_ibge', DB::raw('count(*) as count'))
                ->whereNotNull('naturalidade_municipio_ibge')
                ->groupBy('naturalidade_municipio_ibge')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'naturalidade_municipio_ibge')
                ->toArray();

            $municipios = (new \App\Services\MunicipioService())->all();
            $stats['naturalidade_municipio'] = [];
            foreach ($natMunStats as $ibge => $count) {
                $mun = $municipios->firstWhere('codigoIbge', $ibge);
                $name = $mun ? "{$mun->nome} - {$mun->uf}" : $ibge;
                $stats['naturalidade_municipio'][$name] = $count;
            }

            // Endereço UF (Estado)
            $stats['endereco_uf'] = $baseQuery->clone()
                ->select('estado', DB::raw('count(*) as count'))
                ->whereNotNull('estado')
                ->groupBy('estado')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'estado')
                ->toArray();

            // Endereço Cidade
            $stats['endereco_cidade'] = $baseQuery->clone()
                ->select('cidade', DB::raw('count(*) as count'))
                ->whereNotNull('cidade')
                ->groupBy('cidade')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'cidade')
                ->toArray();

            // Endereço Bairro
            $stats['endereco_bairro'] = $baseQuery->clone()
                ->select('bairro', DB::raw('count(*) as count'))
                ->whereNotNull('bairro')
                ->groupBy('bairro')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'bairro')
                ->toArray();
                
            // Helper to map stats to Enum labels
            $mapEnumStats = function($query, $column, $enumClass) {
                $rawStats = $query->clone()
                    ->select($column, DB::raw('count(*) as count'))
                    ->whereNotNull($column)
                    ->groupBy($column)
                    ->pluck('count', $column)
                    ->toArray();
                
                $mapped = [];
                foreach ($rawStats as $key => $value) {
                    $label = $enumClass::tryFrom($key)?->getLabel() ?? $key;
                    $mapped[$label] = $value;
                }
                arsort($mapped);
                return $mapped;
            };

            // Raça/Cor
            $stats['raca'] = $mapEnumStats($baseQuery, 'raca', \App\Raca::class);

            // Religião
            $stats['religiao'] = $mapEnumStats($baseQuery, 'religiao', \App\Religiao::class);

            // Escolaridade
            $stats['escolaridade'] = $mapEnumStats($baseQuery, 'escolaridade', \App\Escolaridade::class);

            // Declaração Sexual
            $stats['declaracao_sexual'] = $mapEnumStats($baseQuery, 'declaracao_sexual', \App\DeclaracaoSexual::class);

            // Tipo de Deficiência
            $stats['tipo_deficiencia'] = $mapEnumStats($baseQuery, 'tipo_deficiencia', \App\TipoDeficiencia::class);

            // Causa da Deficiência
            $stats['causa_deficiencia'] = $mapEnumStats($baseQuery, 'causa_deficiencia', \App\CausaDeficiencia::class);

            // Aparelhos Utilizados (Top 10)
            $aparelhos = $baseQuery->clone()
                ->whereNotNull('aparelhos_utilizado')
                ->pluck('aparelhos_utilizado')
                ->flatten()
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take(10);
            
            $stats['aparelhos_utilizado'] = [];
            foreach ($aparelhos as $key => $count) {
                $label = \App\AparelhoUtilizado::tryFrom($key)?->getLabel() ?? $key;
                $stats['aparelhos_utilizado'][$label] = $count;
            }

            // CID-10 (Top 10)
            $cids = $baseQuery->clone()
                ->whereNotNull('cid10')
                ->pluck('cid10')
                ->flatten()
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take(10);

            $stats['cid10'] = [];
            if ($cids->isNotEmpty()) {
                $cidModels = \App\Models\Cid10::whereIn('id', $cids->keys())->get()->keyBy('id');
                foreach ($cids as $id => $count) {
                    $model = $cidModels->get($id);
                    $label = $model ? "{$model->codigo} - " . \Illuminate\Support\Str::limit($model->descricao, 30) : "ID: $id";
                    $stats['cid10'][$label] = $count;
                }
            }

            // Ocupações (Top 10)
            $ocupacoes = $baseQuery->clone()
                ->whereNotNull('ocupacoes')
                ->pluck('ocupacoes')
                ->flatten()
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take(10);
            
            $stats['ocupacoes'] = [];
            foreach ($ocupacoes as $key => $count) {
                $label = \App\Ocupacao::tryFrom($key)?->getLabel() ?? $key;
                $stats['ocupacoes'][$label] = $count;
            }

            // Benefícios (Top 10)
            $beneficios = DB::table('associado_beneficio')
                ->whereIn('associado_id', $baseQuery->clone()->select('id'))
                ->select('beneficio_id', DB::raw('count(*) as count'))
                ->groupBy('beneficio_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            $stats['beneficios'] = [];
            if ($beneficios->isNotEmpty()) {
                $beneficioModels = \App\Models\Beneficio::whereIn('id', $beneficios->pluck('beneficio_id'))->get()->keyBy('id');
                foreach ($beneficios as $item) {
                    $model = $beneficioModels->get($item->beneficio_id);
                    $label = $model ? $model->nome : "ID: {$item->beneficio_id}";
                    $stats['beneficios'][$label] = $item->count;
                }
            }

            // Status
            $stats['status'] = $mapEnumStats($baseQuery, 'status', \App\AssociadoStatus::class);

            // Tempo de Associação
            $stats['tempo_associacao'] = [
                '< 1 ano' => $baseQuery->clone()->whereDate('created_at', '>', now()->subYear())->count(),
                '1-3 anos' => $baseQuery->clone()->whereDate('created_at', '<=', now()->subYear())->whereDate('created_at', '>', now()->subYears(3))->count(),
                '3-5 anos' => $baseQuery->clone()->whereDate('created_at', '<=', now()->subYears(3))->whereDate('created_at', '>', now()->subYears(5))->count(),
                '5-10 anos' => $baseQuery->clone()->whereDate('created_at', '<=', now()->subYears(5))->whereDate('created_at', '>', now()->subYears(10))->count(),
                '10+ anos' => $baseQuery->clone()->whereDate('created_at', '<=', now()->subYears(10))->count(),
            ];
        }

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
            
            // Handle Ternary/Boolean
            if ($filter instanceof \Filament\Tables\Filters\TernaryFilter) {
                 $displayValue = $value ? 'Sim' : 'Não';
            }

            $formatted[$name] = [
                'label' => $label,
                'value' => is_array($displayValue) ? implode(', ', $displayValue) : $displayValue
            ];
        }
        
        return $formatted;
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

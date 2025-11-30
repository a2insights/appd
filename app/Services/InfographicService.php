<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InfographicService
{
    public function getStats(Builder $query): array
    {
        $stats = [];
        $baseQuery = $query->clone();

        // Total
        $stats['total'] = $baseQuery->count();

        if ($stats['total'] > 0) {
            // Sexo
            $stats['sexo'] = $baseQuery->clone()
                ->select('sexo', DB::raw('count(*) as count'))
                ->groupBy('sexo')
                ->pluck('count', 'sexo')
                ->mapWithKeys(function ($count, $key) {
                    return [($key ?: 'Não Informado') => $count];
                })
                ->toArray();

            // Faixa Etária
            $stats['faixa_etaria'] = [
                '18-25' => $baseQuery->clone()->whereRaw('TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 18 AND 25')->count(),
                '26-35' => $baseQuery->clone()->whereRaw('TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 26 AND 35')->count(),
                '36-45' => $baseQuery->clone()->whereRaw('TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 36 AND 45')->count(),
                '46-60' => $baseQuery->clone()->whereRaw('TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) BETWEEN 46 AND 60')->count(),
                '60+' => $baseQuery->clone()->whereRaw('TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) > 60')->count(),
            ];

            // Naturalidade UF
            $natUfStats = $baseQuery->clone()
                ->select('naturalidade_uf', DB::raw('count(*) as count'))
                ->groupBy('naturalidade_uf')
                ->orderBy('count', 'desc')
                ->pluck('count', 'naturalidade_uf')
                ->toArray();
            
            $stats['naturalidade_uf'] = [];
            foreach ($natUfStats as $key => $value) {
                $label = $key ?: 'Não Informado';
                $stats['naturalidade_uf'][$label] = ($stats['naturalidade_uf'][$label] ?? 0) + $value;
            }

            // Naturalidade Município (Top 10)
            $natMunStats = $baseQuery->clone()
                ->select('naturalidade_municipio_ibge', DB::raw('count(*) as count'))
                ->whereNotNull('naturalidade_municipio_ibge')
                ->groupBy('naturalidade_municipio_ibge')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'naturalidade_municipio_ibge')
                ->toArray();

            $stats['naturalidade_municipio'] = [];
            if (!empty($natMunStats)) {
                $municipioService = app(\App\Services\MunicipioService::class);
                $municipios = collect($municipioService->all())->keyBy('codigoIbge');
                
                foreach ($natMunStats as $id => $count) {
                    $municipio = $municipios->get($id);
                    $label = $municipio ? "{$municipio->nome} - {$municipio->uf}" : "ID: $id";
                    $stats['naturalidade_municipio'][$label] = $count;
                }
            }

            // Endereço UF (Estado)
            $endUfStats = $baseQuery->clone()
                ->select('estado', DB::raw('count(*) as count'))
                ->groupBy('estado')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'estado')
                ->toArray();
            
            $stats['endereco_uf'] = [];
            foreach ($endUfStats as $key => $value) {
                $label = $key ?: 'Não Informado';
                $stats['endereco_uf'][$label] = ($stats['endereco_uf'][$label] ?? 0) + $value;
            }

            // Endereço Cidade
            $endCidadeStats = $baseQuery->clone()
                ->select('cidade', DB::raw('count(*) as count'))
                ->groupBy('cidade')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'cidade')
                ->toArray();

            $stats['endereco_cidade'] = [];
            foreach ($endCidadeStats as $key => $value) {
                $label = $key ?: 'Não Informado';
                $stats['endereco_cidade'][$label] = ($stats['endereco_cidade'][$label] ?? 0) + $value;
            }

            // Endereço Bairro
            $endBairroStats = $baseQuery->clone()
                ->select('bairro', DB::raw('count(*) as count'))
                ->groupBy('bairro')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'bairro')
                ->toArray();

            $stats['endereco_bairro'] = [];
            foreach ($endBairroStats as $key => $value) {
                $label = $key ?: 'Não Informado';
                $stats['endereco_bairro'][$label] = ($stats['endereco_bairro'][$label] ?? 0) + $value;
            }
                
            // Helper to map stats to Enum labels
            $mapEnumStats = function($query, $column, $enumClass) {
                $rawStats = $query->clone()
                    ->select($column, DB::raw('count(*) as count'))
                    ->groupBy($column)
                    ->pluck('count', $column)
                    ->toArray();
                
                $mapped = [];
                foreach ($rawStats as $key => $value) {
                    if (blank($key)) {
                        $label = 'Não Informado';
                    } else {
                        $label = $enumClass::tryFrom($key)?->getLabel() ?? $key;
                    }
                    
                    if (isset($mapped[$label])) {
                        $mapped[$label] += $value;
                    } else {
                        $mapped[$label] = $value;
                    }
                }
                arsort($mapped);
                return $mapped;
            };

            // Raça/Cor
            $stats['raca'] = $mapEnumStats($baseQuery, 'raca', \App\Raca::class);

            // Estado Civil
            $stats['estado_civil'] = $mapEnumStats($baseQuery, 'estado_civil', \App\EstadoCivil::class);

            // Religião
            $stats['religiao'] = $mapEnumStats($baseQuery, 'religiao', \App\Religiao::class);

            // Escolaridade
            $stats['escolaridade'] = $mapEnumStats($baseQuery, 'escolaridade', \App\Escolaridade::class);

            // Declaração Sexual
            $stats['declaracao_sexual'] = $baseQuery->clone()
                ->select('declaracao_sexual', DB::raw('count(*) as count'))
                ->groupBy('declaracao_sexual')
                ->pluck('count', 'declaracao_sexual')
                ->mapWithKeys(function ($count, $key) {
                    if (blank($key)) {
                        return ['Não Informado' => $count];
                    }
                    $label = \App\DeclaracaoSexual::tryFrom($key)?->getLabel() ?? $key;
                    return [$label => $count];
                })
                ->toArray();

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
                    $label = $model ? "{$model->codigo} - " . Str::limit($model->descricao, 30) : "ID: $id";
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

        return $stats;
    }
}

<?php

namespace App\Filament\Filters;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\Municipio;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\Raca;
use App\Religiao;
use App\Sexo;
use App\TipoDeficiencia;
use Facades\App\Services\MunicipioService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AssociadoFiltersTable
{
    public static function filters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(AssociadoStatus::class)
                ->multiple(),
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                ->label('Data de Cadastro'),
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('carteirinhas.data_emissao')
                ->query(function ($query, array $data) {
                    $date = $data['carteirinhas']['data_emissao'];
                    $date = explode('-', $date);
                    if (! isset($date[0]) || ! isset($date[1])) {
                        return;
                    }

                    $start = trim($date[0]);
                    $end = trim($date[1]);

                    $start = Carbon::createFromFormat('d/m/Y', $start);
                    $end = Carbon::createFromFormat('d/m/Y', $end);

                    $data['values'] = [$start, $end];

                    $query->whereHas('carteirinhas', function ($query) use ($data) {
                        $query->whereBetween('data_emissao', $data['values']);
                    })
                        ->has('carteirinhas', '>', 1); // Garante que tenha mais de uma carteirinha
                })
                ->label('Data de Renovação'),
            SelectFilter::make('sexo')
                ->options(Sexo::class),
            SelectFilter::make('declaracao_sexual')
                ->options(DeclaracaoSexual::class)
                ->multiple(),
            // SelectFilter::make('orgao_expedidor')
            //     ->options(OrgaoExpedidor::class)
            //     ->multiple(),
            // SelectFilter::make('orgao_expedidor_uf')
            //     ->options(OrgaoExpedidorUf::class)
            //     ->multiple(),
            SelectFilter::make('estado_civil')
                ->options(EstadoCivil::class)
                ->multiple(),
            SelectFilter::make('naturalidade_uf')
                ->options(NaturalidadeUf::class)
                ->multiple(),
            // SelectFilter::make('naturalidade_municipio_ibge')
            //     ->options(fn (): array => self::getMunicipios()->pluck('nome', 'id')->all())
            //     ->query(fn (Get $get): array => self::getMunicipiosByUf($get('naturalidade_uf')))
            //     ->multiple(),
            SelectFilter::make('religiao')
                ->options(Religiao::class)
                ->multiple(),
            SelectFilter::make('tipo_deficiencia')
                ->options(TipoDeficiencia::class)
                ->multiple(),
            SelectFilter::make('causa_deficiencia')
                ->options(CausaDeficiencia::class)
                ->multiple(),
            SelectFilter::make('escolaridade')
                ->options(Escolaridade::class)
                ->multiple(),
            SelectFilter::make('raca')
                ->options(Raca::class)
                ->multiple(),
            SelectFilter::make('aparelhos_utilizado')
                ->options(AparelhoUtilizado::class)
                ->multiple(),
            SelectFilter::make('beneficios')
                ->relationship('beneficios', 'nome')
                ->preload()
                ->multiple(),
            SelectFilter::make('cid10')
                ->relationship('cid10', 'codigo')
                ->multiple(),
            SelectFilter::make('ocupacoes')
                ->options(Ocupacao::class)
                ->multiple(),
            SelectFilter::make('cidade')
                ->options(fn (): array => self::getMunicipios()->mapWithKeys(fn (Municipio $item) => [$item->nome => $item->nome])->all())
                ->query(function ($query, array $data) {
                    if (! isset($data['values']) || count($data['values']) === 0) {
                        return;
                    }

                    $query->whereIn('cidade', $data['values']);
                })
                ->multiple(),
            // SelectFilter::make('bairro')
            //     ->multiple()
            //     ->options(fn (): array => self::getBairros()->all())
            //     ->query(function ($query, array $data) {
            //         if (! $data['values']) {
            //             return;
            //         }

            //         $query->whereIn('bairro', $data['values']);
            //     }),
            SelectFilter::make('idade')
                ->options([
                    '0-18' => '0-18 anos',
                    '19-30' => '19-30 anos',
                    '31-50' => '31-50 anos',
                    '51-65' => '51-65 anos',
                    '65+' => '65+ anos',
                ])
                ->query(function ($query, array $data) {
                    if (empty($data['value'])) {
                        return;
                    }

                    $currentYear = Carbon::now()->year;
                    $ageRange = $data['value'];

                    switch ($ageRange) {
                        case '0-18':
                            $query->whereYear('data_nascimento', '>=', $currentYear - 18);
                            break;
                        case '19-30':
                            $query->whereYear('data_nascimento', '<=', $currentYear - 19)
                                ->whereYear('data_nascimento', '>=', $currentYear - 30);
                            break;
                        case '31-50':
                            $query->whereYear('data_nascimento', '<=', $currentYear - 31)
                                ->whereYear('data_nascimento', '>=', $currentYear - 50);
                            break;
                        case '51-65':
                            $query->whereYear('data_nascimento', '<=', $currentYear - 51)
                                ->whereYear('data_nascimento', '>=', $currentYear - 65);
                            break;
                        case '65+':
                            $query->whereYear('data_nascimento', '<=', $currentYear - 66);
                            break;
                    }
                }),
            SelectFilter::make('aniversariantes')
                ->attribute('data_nascimento')
                ->multiple()
                ->options([
                    1 => 'Janeiro',
                    2 => 'Fevereiro',
                    3 => 'Março',
                    4 => 'Abril',
                    5 => 'Maio',
                    6 => 'Junho',
                    7 => 'Julho',
                    8 => 'Agosto',
                    9 => 'Setembro',
                    10 => 'Outubro',
                    11 => 'Novembro',
                    12 => 'Dezembro',
                ])
                ->query(function ($query, array $data) {
                    if (! isset($data['values']) || count($data['values']) === 0) {
                        return;
                    }

                    $query->whereRaw('MONTH(data_nascimento) IN ('.implode(',', $data['values']).')');
                }),
            Filter::make('idade_custom')
                ->form([
                    Repeater::make('age_ranges')
                        ->label('Intervalos de Idade')
                        ->schema([
                            TextInput::make('min_age')
                                ->label('Idade Mínima')
                                ->numeric()
                                ->minValue(0)
                                ->placeholder('Ex: 18')
                                ->rules(['nullable', 'integer', 'min:0'])
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                    if ($state !== null && $get('max_age') !== null && $state > $get('max_age')) {
                                        $set('max_age', $state);
                                    }
                                }),
                            TextInput::make('max_age')
                                ->label('Idade Máxima')
                                ->numeric()
                                ->minValue(0)
                                ->placeholder('Ex: 65')
                                ->rules(function (Get $get) {
                                    return [
                                        'nullable',
                                        'integer',
                                        'min:0',
                                        function ($attribute, $value, $fail) use ($get) {
                                            $minAge = (int) $get('min_age');
                                            if ($minAge !== 0 && $value !== null && (int) $value < $minAge) {
                                                $fail('A idade máxima deve ser maior ou igual à idade mínima.');
                                            }
                                        },
                                    ];
                                })
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                    if ($state !== null && $get('min_age') !== null && $state < $get('min_age')) {
                                        $set('min_age', $state);
                                    }
                                }),
                        ])
                        ->defaultItems(1)
                        ->columns(2)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => ($min = $state['min_age'] ?? null) && ($max = $state['max_age'] ?? null) ? "{$min} - {$max} anos" : null),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    if (empty($data['age_ranges'])) {
                        return $query;
                    }

                    $currentDate = Carbon::now();
                    $query->where(function (Builder $subQuery) use ($data, $currentDate) {
                        foreach ($data['age_ranges'] as $range) {
                            $minAge = (int) ($range['min_age'] ?? null);
                            $maxAge = (int) ($range['max_age'] ?? null);

                            if ($minAge === 0 && $maxAge === 0) {
                                continue;
                            }

                            $subQuery->orWhere(function (Builder $innerSubQuery) use ($minAge, $maxAge, $currentDate) {
                                if ($maxAge > 0 && $minAge > 0) {
                                    $birthYearMax = $currentDate->year - $minAge;
                                    $birthYearMin = $currentDate->year - $maxAge;
                                    $innerSubQuery->whereYear('data_nascimento', '>=', $birthYearMin)
                                        ->whereYear('data_nascimento', '<=', $birthYearMax);

                                } elseif ($minAge > 0) {
                                    $birthYearMax = $currentDate->year - $minAge;
                                    $innerSubQuery->whereYear('data_nascimento', '<=', $birthYearMax);
                                } elseif ($maxAge > 0) {
                                    $birthYearMin = $currentDate->year - $maxAge;
                                    $innerSubQuery->whereYear('data_nascimento', '>=', $birthYearMin);
                                }
                            });
                        }
                    });

                    return $query;
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if (! empty($data['age_ranges'])) {
                        foreach ($data['age_ranges'] as $range) {
                            $minAge = $range['min_age'] ?? null;
                            $maxAge = $range['max_age'] ?? null;

                            if ($minAge !== null && $maxAge !== null) {
                                $indicators["idade_{$minAge}_{$maxAge}"] = "Idade: {$minAge}-{$maxAge} anos";
                            } elseif ($minAge !== null) {
                                $indicators["idade_min_{$minAge}"] = "Idade: {$minAge}+ anos";
                            } elseif ($maxAge !== null) {
                                $indicators["idade_max_{$maxAge}"] = "Idade: até {$maxAge} anos";
                            }
                        }
                    }

                    return $indicators;
                })
                ->columnSpanFull(),
        ];
    }

    /*
    * @return Collection<\App\Municipio>
    */
    private static function getMunicipios(): Collection
    {
        return MunicipioService::all();
    }
}

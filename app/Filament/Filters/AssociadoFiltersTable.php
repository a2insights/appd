<?php

namespace App\Filament\Filters;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\Raca;
use App\Religiao;
use App\Sexo;
use App\TipoDeficiencia;
use Facades\App\Services\MunicipioService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AssociadoFiltersTable
{
    public static function filters(): array
    {
        return [
            // ========================================
            // FILTROS BÁSICOS DE IDENTIFICAÇÃO
            // ========================================

            SelectFilter::make('status')
                ->label('Status')
                ->options(AssociadoStatus::class)
                ->multiple()
                ->searchable()
                ->preload(),

            SelectFilter::make('sexo')
                ->label('Sexo')
                ->options(Sexo::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('declaracao_sexual')
                ->label('Declaração Sexual')
                ->options(DeclaracaoSexual::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('estado_civil')
                ->label('Estado Civil')
                ->options(EstadoCivil::class)
                ->multiple()
                ->searchable(),

            // ========================================
            // FILTROS DE DATAS
            // ========================================

            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                ->label('Data de Cadastro')
                ->placeholder('Selecione o período'),

            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_nascimento')
                ->label('Data de Nascimento')
                ->placeholder('Selecione o período'),

            // Filtro de renovação de carteirinha
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('carteirinhas.data_emissao')
                ->query(function ($query, array $data) {
                    $date = $data['carteirinhas']['data_emissao'] ?? null;
                    if (! $date) {
                        return;
                    }

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

            // ========================================
            // FILTROS DE IDADE (MELHORADOS)
            // ========================================

            SelectFilter::make('idade')
                ->label('Faixa de Idade')
                ->options([
                    // === CRIANÇAS E ADOLESCENTES ===
                    '0-5' => '👶 0-5 anos (Primeira Infância)',
                    '6-11' => '🧒 6-11 anos (Criança)',
                    '12-17' => '👦 12-17 anos (Adolescente)',

                    // === JOVENS ===
                    '18-24' => '🎓 18-24 anos (Jovem)',
                    '25-29' => '💼 25-29 anos (Jovem Adulto)',

                    // === ADULTOS ===
                    '30-39' => '👨 30-39 anos (Adulto)',
                    '40-49' => '👔 40-49 anos (Adulto)',
                    '50-59' => '🧑 50-59 anos (Adulto)',

                    // === IDOSOS ===
                    '60-69' => '👴 60-69 anos (Idoso)',
                    '70-79' => '🧓 70-79 anos (Idoso)',
                    '80+' => '👵 80+ anos (Longevo)',

                    // === FAIXAS ESPECIAIS ===
                    '0-12' => '📚 0-12 anos (Educação Infantil)',
                    '13-18' => '🎒 13-18 anos (Educação Básica)',
                    '18-29' => '🎯 18-29 anos (Jovem Adulto)',
                    '30-59' => '💪 30-59 anos (Adulto Ativo)',
                    '60+' => '🌟 60+ anos (Melhor Idade)',
                ])
                ->query(function ($query, array $data) {
                    if (empty($data['value'])) {
                        return;
                    }

                    $currentYear = Carbon::now()->year;
                    $ageRange = $data['value'];

                    // Extrair min e max da string (ex: '0-5' -> min=0, max=5)
                    if (str_contains($ageRange, '+')) {
                        // Casos especiais: 60+, 80+
                        $minAge = (int) str_replace('+', '', $ageRange);
                        $query->whereYear('data_nascimento', '<=', $currentYear - $minAge);
                    } else {
                        // Casos normais: 0-5, 6-11, etc.
                        [$minAge, $maxAge] = explode('-', $ageRange);
                        $minAge = (int) $minAge;
                        $maxAge = (int) $maxAge;

                        $birthYearMax = $currentYear - $minAge;
                        $birthYearMin = $currentYear - $maxAge;

                        $query->whereYear('data_nascimento', '>=', $birthYearMin)
                            ->whereYear('data_nascimento', '<=', $birthYearMax);
                    }
                })
                ->searchable()
                ->placeholder('Selecione uma faixa'),

            Filter::make('idade_custom')
                ->label('Faixa de Idade (Personalizada)')
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

            SelectFilter::make('aniversariantes')
                ->label('Aniversariantes do Mês')
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
                })
                ->searchable(),

            // ========================================
            // FILTROS DE LOCALIZAÇÃO
            // ========================================

            Filter::make('naturalidade')
                ->label('Localização (Naturalidade)')
                ->form([
                    \Filament\Forms\Components\Grid::make(2)
                        ->schema([
                            Select::make('naturalidade_uf')
                                ->label('UF de Naturalidade')
                                ->options(NaturalidadeUf::class)
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('naturalidade_municipio_ibge', null)),

                            Select::make('naturalidade_municipio_ibge')
                                ->label('Naturalidade (Município)')
                                ->multiple()
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search, Get $get) {
                                    $ufs = $get('naturalidade_uf');
                                    if (empty($ufs)) {
                                        return [];
                                    }

                                    if (! is_array($ufs)) {
                                        $ufs = [$ufs];
                                    }

                                    $service = app(\App\Services\MunicipioService::class);
                                    $municipios = collect();

                                    foreach ($ufs as $uf) {
                                        $municipios = $municipios->merge($service->allByUf($uf));
                                    }

                                    return $municipios
                                        ->filter(fn ($item) => stripos($item->nome, $search) !== false)
                                        ->sortBy('nome')
                                        ->take(50)
                                        ->mapWithKeys(fn ($item) => [$item->codigoIbge => $item->nome])
                                        ->all();
                                })
                                ->getOptionLabelsUsing(function (array $values) {
                                    $service = app(\App\Services\MunicipioService::class);

                                    // Otimização: Se forem poucos valores, busca individualmente (cacheado).
                                    // Se forem muitos, poderia ser melhor carregar tudo, mas find() é seguro.
                                    return collect($values)->mapWithKeys(function ($codigo) use ($service) {
                                        $municipio = $service->find($codigo);

                                        return [$codigo => $municipio ? $municipio->nome : $codigo];
                                    })->all();
                                }),
                        ]),
                ])
                ->query(function (Builder $query, array $data) {
                    if (! empty($data['naturalidade_uf'])) {
                        $query->whereIn('naturalidade_uf', $data['naturalidade_uf']);
                    }
                    if (! empty($data['naturalidade_municipio_ibge'])) {
                        $query->whereIn('naturalidade_municipio_ibge', $data['naturalidade_municipio_ibge']);
                    }
                })
                ->columnSpan(2),

            Filter::make('endereco_avancado')
                ->label('Endereço (Busca Avançada)')
                ->form([
                    \Filament\Forms\Components\Actions::make([
                        \Filament\Forms\Components\Actions\Action::make('add_para')
                            ->label('Adicionar Estado: Pará')
                            ->icon('heroicon-m-plus-circle')
                            ->action(function (callable $set, Get $get) {
                                $state = $get('condicoes') ?? [];

                                // Verifica se já existe um filtro para estado = pa
                                $exists = collect($state)->contains(function ($item) {
                                    $val = $item['value'] ?? null;

                                    return ($item['field'] ?? '') === 'estado' && (is_array($val) ? in_array('pa', $val) : $val === 'pa');
                                });

                                if (! $exists) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'estado',
                                        'operator' => 'equals',
                                        'value' => ['pa'],
                                    ];
                                    $set('condicoes', $state);
                                }
                            }),
                        \Filament\Forms\Components\Actions\Action::make('add_belem')
                            ->label('Adicionar Cidade: Belém')
                            ->icon('heroicon-m-plus-circle')
                            ->action(function (callable $set, Get $get) {
                                $state = $get('condicoes') ?? [];

                                // Helper para verificar existência
                                $hasFilter = function ($field, $value) use ($state) {
                                    return collect($state)->contains(function ($item) use ($field, $value) {
                                        if (($item['field'] ?? '') !== $field) {
                                            return false;
                                        }
                                        $val = $item['value'] ?? null;

                                        return is_array($val) ? in_array($value, $val) : $val === $value;
                                    });
                                };

                                // Garante Estado = PA
                                if (! $hasFilter('estado', 'pa')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'estado',
                                        'operator' => 'equals',
                                        'value' => ['pa'],
                                    ];
                                }

                                // Garante Cidade = Belém
                                if (! $hasFilter('cidade', 'Belém')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'cidade',
                                        'operator' => 'equals',
                                        'value' => ['Belém'],
                                    ];
                                }

                                $set('condicoes', $state);
                            }),
                        \Filament\Forms\Components\Actions\Action::make('add_pedreira')
                            ->label('Adicionar Bairro: Pedreira')
                            ->icon('heroicon-m-plus-circle')
                            ->action(function (callable $set, Get $get) {
                                $state = $get('condicoes') ?? [];

                                // Helper para verificar existência
                                $hasFilter = function ($field, $value) use ($state) {
                                    return collect($state)->contains(function ($item) use ($field, $value) {
                                        if (($item['field'] ?? '') !== $field) {
                                            return false;
                                        }
                                        $val = $item['value'] ?? null;

                                        return is_array($val) ? in_array($value, $val) : $val === $value;
                                    });
                                };

                                // Garante Estado = PA
                                if (! $hasFilter('estado', 'pa')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'estado',
                                        'operator' => 'equals',
                                        'value' => ['pa'],
                                    ];
                                }

                                // Garante Cidade = Belém
                                if (! $hasFilter('cidade', 'Belém')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'cidade',
                                        'operator' => 'equals',
                                        'value' => ['Belém'],
                                    ];
                                }

                                // Adiciona Bairro = Pedreira
                                if (! $hasFilter('bairro', 'Pedreira')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'bairro',
                                        'operator' => 'contains',
                                        'value' => ['Pedreira'],
                                    ];
                                }

                                $set('condicoes', $state);
                            }),
                        \Filament\Forms\Components\Actions\Action::make('add_pedro_miranda')
                            ->label('Adicionar Rua: Av. Pedro Miranda')
                            ->icon('heroicon-m-plus-circle')
                            ->action(function (callable $set, Get $get) {
                                $state = $get('condicoes') ?? [];

                                // Helper para verificar existência
                                $hasFilter = function ($field, $value) use ($state) {
                                    return collect($state)->contains(function ($item) use ($field, $value) {
                                        if (($item['field'] ?? '') !== $field) {
                                            return false;
                                        }
                                        $val = $item['value'] ?? null;

                                        return is_array($val) ? in_array($value, $val) : $val === $value;
                                    });
                                };

                                // Garante Estado = PA
                                if (! $hasFilter('estado', 'pa')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'estado',
                                        'operator' => 'equals',
                                        'value' => ['pa'],
                                    ];
                                }

                                // Garante Cidade = Belém
                                if (! $hasFilter('cidade', 'Belém')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'cidade',
                                        'operator' => 'equals',
                                        'value' => ['Belém'],
                                    ];
                                }

                                // Garante Bairro = Pedreira
                                if (! $hasFilter('bairro', 'Pedreira')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'bairro',
                                        'operator' => 'contains',
                                        'value' => ['Pedreira'],
                                    ];
                                }

                                // Adiciona Rua = Avenida Pedro Miranda
                                if (! $hasFilter('rua', 'Avenida Pedro Miranda')) {
                                    $state[] = [
                                        'logic' => empty($state) ? 'and' : 'and',
                                        'field' => 'rua',
                                        'operator' => 'contains',
                                        'value' => ['Avenida Pedro Miranda'],
                                    ];
                                }

                                $set('condicoes', $state);
                            }),
                    ]),
                    Repeater::make('condicoes')
                        ->label('Condições')
                        ->itemLabel(fn (array $state): ?string => (function () use ($state) {
                            $logic = match ($state['logic'] ?? 'and') {
                                'and' => 'E',
                                'or' => 'OU',
                                'and_not' => 'E NÃO',
                                'or_not' => 'OU NÃO',
                                default => 'E',
                            };

                            $field = match ($state['field'] ?? '') {
                                'estado' => 'Estado',
                                'cidade' => 'Cidade',
                                'bairro' => 'Bairro',
                                'rua' => 'Rua',
                                'cep' => 'CEP',
                                default => ucfirst($state['field'] ?? 'Campo'),
                            };

                            $operator = match ($state['operator'] ?? 'contains') {
                                'equals' => 'igual a',
                                'contains' => 'contém',
                                'starts_with' => 'começa com',
                                'ends_with' => 'termina com',
                                'not_equals' => 'diferente de',
                                'not_contains' => 'não contém',
                                'empty' => 'vazio',
                                'not_empty' => 'preenchido',
                                default => $state['operator'] ?? '',
                            };

                            $value = $state['value'] ?? '';
                            if (is_array($value)) {
                                $value = implode(', ', $value);
                            }

                            if (in_array($state['operator'] ?? '', ['empty', 'not_empty'])) {
                                return "{$logic} {$field} {$operator}";
                            }

                            return "{$logic} {$field} {$operator} \"{$value}\"";
                        })())
                        ->schema([
                            \Filament\Forms\Components\Grid::make(4)
                                ->schema([
                                    Select::make('logic')
                                        ->label('Lógica')
                                        ->options([
                                            'and' => 'E (AND)',
                                            'or' => 'OU (OR)',
                                            'and_not' => 'E NÃO (AND NOT)',
                                            'or_not' => 'OU NÃO (OR NOT)',
                                        ])
                                        ->default('and')
                                        ->required()
                                        ->disablePlaceholderSelection(),
                                    Select::make('field')
                                        ->label('Campo')
                                        ->options([
                                            'estado' => 'Estado (UF)',
                                            'cidade' => 'Cidade',
                                            'bairro' => 'Bairro',
                                            'rua' => 'Rua/Logradouro',
                                            'cep' => 'CEP',
                                        ])
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn (callable $set) => $set('value', null))
                                        ->rules([
                                            fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                                if (in_array($value, ['bairro', 'rua'])) {
                                                    // Tenta buscar o estado do repeater.
                                                    // Nota: O caminho relativo pode variar dependendo da versão do Filament e estrutura.
                                                    // Assumindo que estamos dentro de um item do repeater.
                                                    $repeaterState = $get('../../condicoes');

                                                    // Se não conseguir acessar via relativo, tenta via nome absoluto do campo no formulário
                                                    if (is_null($repeaterState)) {
                                                        // Fallback: em alguns contextos o Get pode não resolver ../.. corretamente
                                                        // Mas como é um Filter form, o state raiz é o form data.
                                                        // Vamos tentar validar apenas se houver 'cidade' no array atual.
                                                        // Porém, o $get('../../condicoes') é o padrão para acessar o pai.
                                                        return;
                                                    }

                                                    $hasCidade = collect($repeaterState)->contains(function ($item) {
                                                        return ($item['field'] ?? '') === 'cidade';
                                                    });

                                                    if (! $hasCidade) {
                                                        $fail('Para filtrar por Bairro ou Rua, você deve adicionar um filtro de Cidade primeiro.');
                                                    }
                                                }
                                            },
                                        ]),
                                    Select::make('operator')
                                        ->label('Operador')
                                        ->options([
                                            'equals' => 'Igual a',
                                            'contains' => 'Contém',
                                            'starts_with' => 'Começa com',
                                            'ends_with' => 'Termina com',
                                            'not_equals' => 'Diferente de',
                                            'not_contains' => 'Não contém',
                                            'empty' => 'Vazio/Nulo',
                                            'not_empty' => 'Preenchido',
                                        ])
                                        ->default('contains')
                                        ->required()
                                        ->reactive(),
                                    Select::make('value')
                                        ->label('Valor')
                                        ->multiple(fn (Get $get) => in_array($get('operator'), ['contains', 'equals', 'not_equals', 'not_contains']))
                                        ->searchable()
                                        ->getSearchResultsUsing(function (string $search, Get $get) {
                                            $field = $get('field');
                                            if (! $field) {
                                                return [];
                                            }

                                            // Para estado e cidade, usamos listas pré-definidas/serviços se possível, ou busca no banco
                                            if ($field === 'estado') {
                                                return collect(\App\NaturalidadeUf::cases())
                                                    ->filter(fn ($case) => str_contains(strtolower($case->getLabel()), strtolower($search)) || str_contains(strtolower($case->value), strtolower($search)))
                                                    ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])
                                                    ->all();
                                            }

                                            if ($field === 'cidade') {
                                                return self::getMunicipios()
                                                    ->filter(fn ($municipio) => str_contains(strtolower($municipio->nome), strtolower($search)))
                                                    ->take(50)
                                                    ->mapWithKeys(fn ($municipio) => [$municipio->nome => $municipio->nome])
                                                    ->all();
                                            }

                                            // Para outros campos, buscamos valores distintos no banco
                                            return \App\Models\Associado::query()
                                                ->where($field, 'like', "%{$search}%")
                                                ->distinct()
                                                ->limit(50)
                                                ->pluck($field, $field)
                                                ->toArray();
                                        })
                                        ->options(function (Get $get) {
                                            $field = $get('field');
                                            $value = $get('value');

                                            if (! $field) {
                                                return [];
                                            }

                                            $options = [];

                                            if ($field === 'estado') {
                                                $options = collect(\App\NaturalidadeUf::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])->all();
                                            } elseif ($field === 'cidade') {
                                                $options = self::getMunicipios()->take(100)->mapWithKeys(fn ($municipio) => [$municipio->nome => $municipio->nome])->all();
                                            }

                                            // Garante que o valor selecionado (via ação ou edição) esteja nas opções para ser exibido corretamente
                                            if ($value) {
                                                $values = is_array($value) ? $value : [$value];
                                                foreach ($values as $v) {
                                                    if (! isset($options[$v])) {
                                                        $options[$v] = $v;
                                                    }
                                                }
                                            }

                                            return $options;
                                        })
                                        ->disabled(fn (Get $get) => in_array($get('operator'), ['empty', 'not_empty'])),
                                ]),
                        ])
                        ->defaultItems(1)
                        ->collapsible()
                        ->cloneable(),
                ])
                ->query(function (Builder $query, array $data) {
                    $conditions = $data['condicoes'] ?? [];

                    if (empty($conditions)) {
                        return $query;
                    }

                    $query->where(function (Builder $subQuery) use ($conditions) {
                        foreach ($conditions as $index => $condition) {
                            $logic = $condition['logic'] ?? 'and';
                            $field = $condition['field'] ?? null;
                            $operator = $condition['operator'] ?? 'contains';
                            $value = $condition['value'] ?? null;

                            if (! $field) {
                                continue;
                            }

                            // Determine method based on logic
                            if ($index === 0) {
                                if ($logic === 'or') {
                                    $logic = 'and';
                                }
                                if ($logic === 'or_not') {
                                    $logic = 'and_not';
                                }
                            }

                            $method = match ($logic) {
                                'or' => 'orWhere',
                                'and_not' => 'whereNot',
                                'or_not' => 'orWhereNot',
                                default => 'where',
                            };

                            $subQuery->$method(function (Builder $q) use ($field, $operator, $value) {
                                $values = is_array($value) ? $value : [$value];

                                switch ($operator) {
                                    case 'equals':
                                        $q->whereIn($field, $values);
                                        break;
                                    case 'not_equals':
                                        $q->whereNotIn($field, $values);
                                        break;
                                    case 'contains':
                                        $q->where(function ($sub) use ($field, $values) {
                                            foreach ($values as $v) {
                                                $sub->orWhere($field, 'like', "%{$v}%");
                                            }
                                        });
                                        break;
                                    case 'not_contains':
                                        $q->where(function ($sub) use ($field, $values) {
                                            foreach ($values as $v) {
                                                $sub->where($field, 'not like', "%{$v}%");
                                            }
                                        });
                                        break;
                                    case 'starts_with':
                                        $q->where(function ($sub) use ($field, $values) {
                                            foreach ($values as $v) {
                                                $sub->orWhere($field, 'like', "{$v}%");
                                            }
                                        });
                                        break;
                                    case 'ends_with':
                                        $q->where(function ($sub) use ($field, $values) {
                                            foreach ($values as $v) {
                                                $sub->orWhere($field, 'like', "%{$v}");
                                            }
                                        });
                                        break;
                                    case 'empty':
                                        $q->whereNull($field)->orWhere($field, '');
                                        break;
                                    case 'not_empty':
                                        $q->whereNotNull($field)->where($field, '!=', '');
                                        break;
                                }
                            });
                        }
                    });
                })
                ->columnSpanFull(),

            // ========================================
            // FILTROS SOCIODEMOGRÁFICOS
            // ========================================

            SelectFilter::make('religiao')
                ->label('Religião')
                ->options(Religiao::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('escolaridade')
                ->label('Escolaridade')
                ->options(Escolaridade::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('raca')
                ->label('Raça/Cor')
                ->options(Raca::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('ocupacoes')
                ->label('Ocupação')
                ->options(Ocupacao::class)
                ->multiple()
                ->searchable(),

            // ========================================
            // FILTROS DE DEFICIÊNCIA
            // ========================================

            TernaryFilter::make('possui_deficiencia')
                ->label('Possui Deficiência?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->whereNotNull('tipo_deficiencia')
                        ->orWhereNotNull('causa_deficiencia')
                        ->orWhereJsonLength('cid10', '>', 0),
                    false: fn (Builder $query) => $query->whereNull('tipo_deficiencia')
                        ->whereNull('causa_deficiencia')
                        ->where(function ($q) {
                            $q->whereNull('cid10')
                                ->orWhereJsonLength('cid10', '=', 0);
                        }),
                ),

            SelectFilter::make('tipo_deficiencia')
                ->label('Tipo de Deficiência')
                ->options(TipoDeficiencia::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('causa_deficiencia')
                ->label('Causa da Deficiência')
                ->options(CausaDeficiencia::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('aparelhos_utilizado')
                ->label('Aparelhos Utilizados')
                ->options(AparelhoUtilizado::class)
                ->multiple()
                ->searchable(),

            SelectFilter::make('cid10')
                ->label('CID-10')
                ->relationship('cid10', 'codigo')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codigo} - {$record->descricao}")
                ->multiple()
                ->searchable()
                ->preload(),

            TernaryFilter::make('possui_crm')
                ->label('Possui CRM?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->whereNotNull('crm')->where('crm', '!=', ''),
                    false: fn (Builder $query) => $query->whereNull('crm')->orWhere('crm', '=', ''),
                ),

            // ========================================
            // FILTROS DE RELACIONAMENTOS
            // ========================================

            SelectFilter::make('beneficios')
                ->label('Benefícios')
                ->relationship('beneficios', 'nome')
                ->preload()
                ->multiple()
                ->searchable(),

            TernaryFilter::make('possui_carteirinha')
                ->label('Possui Carteirinha?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->has('carteirinhas'),
                    false: fn (Builder $query) => $query->doesntHave('carteirinhas'),
                ),

            TernaryFilter::make('possui_talento')
                ->label('Possui Talento Cadastrado?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->has('talento'),
                    false: fn (Builder $query) => $query->doesntHave('talento'),
                ),

            // ========================================
            // FILTROS DE CONTATO
            // ========================================

            TernaryFilter::make('possui_whatsapp')
                ->label('Possui WhatsApp?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->whereNotNull('telefone_whatsapp')->where('telefone_whatsapp', '!=', ''),
                    false: fn (Builder $query) => $query->whereNull('telefone_whatsapp')->orWhere('telefone_whatsapp', '=', ''),
                ),

            TernaryFilter::make('possui_email')
                ->label('Possui E-mail?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->whereNotNull('email')->where('email', '!=', ''),
                    false: fn (Builder $query) => $query->whereNull('email')->orWhere('email', '=', ''),
                ),

            TernaryFilter::make('possui_foto')
                ->label('Possui Foto?')
                ->placeholder('Todos')
                ->trueLabel('Sim')
                ->falseLabel('Não')
                ->queries(
                    true: fn (Builder $query) => $query->whereNotNull('foto')->where('foto', '!=', ''),
                    false: fn (Builder $query) => $query->whereNull('foto')->orWhere('foto', '=', ''),
                ),
        ];
    }

    /**
     * @return Collection<\App\Municipio>
     */
    private static function getMunicipios(): Collection
    {
        return MunicipioService::all();
    }
}

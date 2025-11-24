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
use Filament\Forms\Components\DatePicker;
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
                    if (!$date) {
                        return;
                    }

                    $date = explode('-', $date);
                    if (!isset($date[0]) || !isset($date[1])) {
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
                    if (!empty($data['age_ranges'])) {
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
                    if (!isset($data['values']) || count($data['values']) === 0) {
                        return;
                    }

                    $query->whereRaw('MONTH(data_nascimento) IN ('.implode(',', $data['values']).')');
                })
                ->searchable(),

            // ========================================
            // FILTROS DE LOCALIZAÇÃO
            // ========================================

            SelectFilter::make('naturalidade_uf')
                ->label('UF de Naturalidade')
                ->options(NaturalidadeUf::class)
                ->multiple()
                ->searchable()
                ->preload(),

            SelectFilter::make('cidade')
                ->label('Cidade (Endereço)')
                ->options(fn (): array => self::getMunicipios()->mapWithKeys(fn (Municipio $item) => [$item->nome => $item->nome])->all())
                ->query(function ($query, array $data) {
                    if (!isset($data['values']) || count($data['values']) === 0) {
                        return;
                    }

                    $query->whereIn('cidade', $data['values']);
                })
                ->multiple()
                ->searchable()
                ->preload(),

            SelectFilter::make('perimetro')
                ->label('Perímetro')
                ->options([
                    'urbano' => 'Urbano',
                    'rural' => 'Rural',
                ])
                ->multiple(),

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

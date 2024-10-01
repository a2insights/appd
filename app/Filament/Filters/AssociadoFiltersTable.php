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
use Filament\Tables\Filters\SelectFilter;
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
            \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_nascimento'),
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

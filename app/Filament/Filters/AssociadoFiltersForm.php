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
use Filament\Forms\Components\Select;
use Illuminate\Support\Collection;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class AssociadoFiltersForm
{
    public static function filters(): array
    {
        return [
            Select::make('status')
                ->options(AssociadoStatus::class)
                ->multiple(),
            DateRangePicker::make('created_at')
                ->autoApply()
                ->disableClear(false)
                ->label('Data'),
            Select::make('sexo')
                ->options(Sexo::class),
            Select::make('declaracao_sexual')
                ->options(DeclaracaoSexual::class)
                ->multiple(),
            Select::make('estado_civil')
                ->options(EstadoCivil::class)
                ->multiple(),
            // Select::make('naturalidade_uf')
            //     ->options(NaturalidadeUf::class)
            //     ->multiple(),
            // Select::make('naturalidade_municipio_ibge')
            //     ->options(fn (): array => self::getMunicipios()->pluck('nome', 'id')->all())
            //     ->query(fn (Get $get): array => self::getMunicipiosByUf($get('naturalidade_uf')))
            //     ->multiple(),
            Select::make('religiao')
                ->options(Religiao::class)
                ->multiple(),
            Select::make('tipo_deficiencia')
                ->options(TipoDeficiencia::class)
                ->multiple(),
            Select::make('causa_deficiencia')
                ->options(CausaDeficiencia::class)
                ->multiple(),
            Select::make('escolaridade')
                ->options(Escolaridade::class)
                ->multiple(),
            Select::make('raca')
                ->options(Raca::class)
                ->multiple(),
            Select::make('aparelhos_utilizado')
                ->options(AparelhoUtilizado::class)
                ->multiple(),
            Select::make('ocupacoes')
                ->options(Ocupacao::class)
                ->multiple(),
        ];
    }

    /*
    * @return Collection<\App\Municipio>
    */
    // private static function getMunicipios(): Collection
    // {
    //     return MunicipioService::all();
    // }
}

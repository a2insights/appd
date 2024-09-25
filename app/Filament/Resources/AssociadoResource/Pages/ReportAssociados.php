<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Filters\AssociadoFiltersForm;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ReportAssociados extends Page
{
    use Dashboard\Concerns\HasFiltersForm;

    protected static string $view = 'filament.resources.associado-resource.pages.report-associados';

    protected static string $routePath = '/report-associados';

    protected static ?int $navigationSort = 2;

    protected ?string $maxContentWidth = 'full';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-chart-bar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Relatório';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Relatório de Associados';
    }

    public function filtersForm(Form $form): Form
    {

        $options = [
            'status' => 'Status',
            'sexo' => 'Sexo',
            'declaracao_sexual' => 'Declaração Sexual',
            'estado_civil' => 'Estado Civil',
            'naturalidade_uf' => 'Naturalidade UF',
            'religiao' => 'Religião',
            'escolaridade' => 'Escolaridade',
            'raca' => 'Raça',
            'causa_deficiencia' => 'Causa da Deficiência',
            'tipo_deficiencia' => 'Tipo de Deficiência',
        ];

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        // Group::make()
                        //     ->schema(AssociadoFiltersForm::filters())
                        //     ->columns(6),
                        Group::make()
                            ->schema([
                                Select::make('xAxis')
                                    ->label('Eixo X')
                                    ->options($options),
                                Select::make('group')
                                    ->label('Agrupar por')
                                    ->options($options),
                            ])
                            ->columns(6),
                    ]),
            ]);
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int|string|array
    {
        return 2;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AssociadosReport::class,
        ];
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }
}

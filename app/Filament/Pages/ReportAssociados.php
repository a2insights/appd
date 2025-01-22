<?php

namespace App\Filament\Pages;

use App\Exports\AssociadosChartExport;
use App\Filament\Filters\AssociadoFiltersForm;
use App\Filament\Widgets\AssociadosReport;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportAssociados extends Page
{
    use Dashboard\Concerns\HasFiltersForm;

    protected static string $view = 'filament.pages.report';

    protected static string $routePath = '/report-associados';

    protected static ?string $navigationGroup = 'Relatórios';

    protected static ?int $navigationSort = 3;

    protected ?string $maxContentWidth = 'full';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-c-chart-pie';
    }

    public static function getNavigationLabel(): string
    {
        return 'Associados';
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
            'ocupacoes' => 'Ocupações',
            'aparelhos_utilizado' => 'Aparelhos Utilizados',
        ];

        return $form
            ->schema([
                Section::make('Filtros')
                    ->description('Filtre os associados por diferentes critérios.')
                    ->icon('heroicon-o-funnel')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Group::make()
                            ->schema(AssociadoFiltersForm::filters())
                            ->columns(6),
                    ]),
                Section::make('Gráfico')
                    ->description('Selecione os eixos do gráfico.')
                    ->icon('heroicon-o-chart-bar')
                    ->headerActions([
                        Action::make('imagem')
                            ->icon('heroicon-o-photo')
                            ->color('gray')
                            ->action(function ($state) {
                                $xAxis = $state['xAxis'];
                                $group = $state['group'];

                                $chartData = (new AssociadosReport)->generateDatasetsAndLabels($xAxis, $group);
                                $optionsData = (new AssociadosReport)->getOptionsData($xAxis, $group);

                                $data = [
                                    'type' => 'bar',
                                    'data' => $chartData,
                                    'options' => [
                                        'title' => @$optionsData['plugins']['title'],
                                        'display' => true,
                                        'maintainAspectRatio' => true,
                                        ...$optionsData['options'],
                                    ],
                                ];

                                $response = Http::post(config('services.serverless.chart_image.url'), $data);

                                $dataURI = $response->json('data.dataURI');
                                $dataURI = str_replace('data:image/png;base64,', '', $dataURI);
                                $fileContents = base64_decode($dataURI);

                                $filePath = 'charts/' . Str::random(40) . '.png';

                                Storage::disk(config('livewire.temporary_file.disk'))
                                    ->put($filePath, $fileContents);

                                return Storage::disk(config('livewire.temporary_file.disk'))->download($filePath);
                            }),
                        Action::make('planilha')
                            ->icon('heroicon-o-table-cells')
                            ->color('gray')
                            ->action(function (array $state) {
                                $xAxis = $state['xAxis'];
                                $group = $state['group'];

                                $chartData = (new AssociadosReport)->generateDatasetsAndLabels($xAxis, $group);
                                $fileName = Str::slug('associados-' . $xAxis . '-' . $group) . Str::random(5) . '.xlsx';

                                $simplifiedData = [];

                                $header = [$group];
                                foreach ($chartData['datasets'] as $dataset) {
                                    $header[] = $dataset['label'];
                                }
                                $simplifiedData[] = $header;

                                foreach ($chartData['labels'] as $index => $label) {
                                    $row = [$label];
                                    foreach ($chartData['datasets'] as $dataset) {
                                        $row[] = $dataset['data'][$index];
                                    }
                                    $simplifiedData[] = $row;
                                }

                                $total = ['Total'];
                                foreach ($chartData['datasets'] as $dataset) {
                                    $total[] = array_sum($dataset['data']);
                                }

                                $simplifiedData[] = $total;

                                return Excel::download(new AssociadosChartExport($simplifiedData), $fileName);
                            }),
                    ])
                    ->schema([
                        Group::make()
                            ->schema([
                                Select::make('xAxis')
                                    ->label('Eixo X')
                                    ->default('status')
                                    ->options($options),
                                Select::make('group')
                                    ->label('Agrupar por')
                                    ->default('sexo')
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

<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form as Schema;
use Filament\Forms\Components\Section;
use Filament\Pages\Dashboard as FilamentDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class Dashboard extends FilamentDashboard
{
    use \Filament\Pages\Dashboard\Concerns\HasFiltersForm;


    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        DateRangePicker::make('date_range')
                            ->label('Período Padrão: 1 este ano')
                            ->default(fn () => [now()->subYear(), now()])
                            ->autoApply()
                            ->disableClear(false)
                            ->placeholder('Selecione um período'),
                    ])
                    ->columns(3),
            ]);
    }
}

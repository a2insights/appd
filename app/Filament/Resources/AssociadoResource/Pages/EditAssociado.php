<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Resources\AssociadoResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Mansoor\FilamentVersionable\Page\RevisionsAction;
use Illuminate\Support\Str;

class EditAssociado extends EditRecord
{
    protected static string $resource = AssociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('download')
                ->label('Baixar')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (Action $action) {
                    $view = view('associados.print', [
                        'record' => $this->record,
                    ]);

                    $pdf = Pdf::loadHTML($view)
                        ->setPaper('A4', 'portrait')
                        ->setOption('isRemoteEnabled', true);

                    $fileName = $this->record->nome.'_'.$this->record->id.'_'.Str::random(5).'.pdf';

                    return response()->streamDownload(
                        fn () => print ($pdf->output()),
                        $fileName
                    );
                })
                ->hidden(fn () => ! $this->record), // Adiciona verificação para ocultar se não houver registro

            RevisionsAction::make()
                ->label('Alterações'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

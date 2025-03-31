<?php

namespace App\Filament\Resources\EncaminhamentoResource\Pages;

use App\Filament\Resources\EncaminhamentoResource;
use App\Models\Encaminhamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditEncaminhamento extends EditRecord
{
    protected static string $resource = EncaminhamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->syncDeclaracao($this->record);

        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
    }

    protected function syncDeclaracao(Encaminhamento $record): void
    {
        $path = 'encaminhamentos/pdfs/encaminhamento-'.Str::slug($record->talento->associado->nome).'-'.$record->id.'.pdf';

        $encaminhamento = [
            'titulo' => 'CARTA DE ENCAMINHAMENTO DE VAGA',
            'pdf' => $path,
        ];

        $record->encaminhamento = $encaminhamento;
        $record->save();
        $record->refresh();

        $record->load(['vaga.cargos', 'talento.associado']);

        $pdf = Pdf::loadView('carta-encaminhamento', [
            'encaminhamento' => $record,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $disk = config('filament.default_filesystem_disk');

        Storage::disk($disk)->put($path, $pdf->output());
    }

    public static function formatDocument($document)
    {
        if (strlen($document) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $document);
        } elseif (strlen($document) === 9) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2.$3', $document);
        }

        return $document;
    }
}

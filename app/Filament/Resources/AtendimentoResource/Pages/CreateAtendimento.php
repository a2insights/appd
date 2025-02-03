<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Resources\AtendimentoResource;
use App\Models\Atendimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateAtendimento extends CreateRecord
{
    protected static string $resource = AtendimentoResource::class;

    protected static bool $canCreateAnother = false;

    public function getBreadcrumb(): string
    {
        return 'Novo Atendimento';
    }

    public function getTitle(): string
    {
        return 'Novo Atendimento';
    }

    protected function afterCreate(): void
    {
        $this->syncDeclaracao([
            'titulo' => 'Declaração de Atendimento',
            'descricao' => $this->getDescription(),
        ], $this->record);

        redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
    }

    protected function getDescription(): string
    {
        $nome = $this->record->associado->nome ?? $this->record->pessoa->nome ?? 'Nome não informado';

        $text = "Declaramos, para os devidos fins, que <b>{$nome}</b> recebeu atendimento em nossa instituição com referência aos seguintes serviços ou atividades realizadas:\n\n";

        $tiposAtendimento = $this->record->tipos->implode('titulo', ', ');

        $text .= "Atendimentos referentes a: <b>{$tiposAtendimento}</b> <br>\n";
        $text .= "Agradecemos pela atenção e colocamo-nos à disposição para eventuais esclarecimentos.\n\n";

        return $text;
    }

    protected function syncDeclaracao(array $data, Atendimento $record): void
    {
        $path = 'atendimentos/pdfs/atendimento-'.Str::slug($record->getNome()).'-'.$record->id.'.pdf';

        $declaracao = [
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'],
            'pdf' => $path,
        ];

        $record->declaracao = $declaracao;
        $record->save();
        $record->refresh();

        $pdf = Pdf::loadView('declaracao-atendimento', ['atendimento' => $record]);
        $pdf->setPaper('a4', 'portrait');

        $disk = config('filament.default_filesystem_disk');

        Storage::disk($disk)->put($path, $pdf->output());
    }
}

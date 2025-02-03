<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Resources\AtendimentoResource;
use App\Models\Atendimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditAtendimento extends EditRecord
{
    protected static string $resource = AtendimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('declaracao')
            //     ->label('Declaração de Atendimento')
            //     ->form([
            //         TextInput::make('titulo')
            //             ->label('Título')
            //             ->default(fn (Atendimento $record) => $record->declaracao['titulo'] ?? 'Declaração de Atendimento')
            //             ->required()
            //             ->suffixAction(
            //                 ActionsAction::make('resetDescription')
            //                     ->icon('heroicon-o-arrow-path')
            //                     ->action(function (Set $set, $state) {
            //                         $set('titulo', 'Declaração de Atendimento');
            //                         $set('descricao', $this->getDescription());
            //                     })
            //             )
            //             ->maxLength(255)
            //             ->maxLength(255),
            //         RichEditor::make('descricao')
            //             ->label('Descrição')
            //             ->required()

            //             ->default(fn (Atendimento $record) => $record->declaracao['descricao'] ?? $this->getDescription())
            //             ->toolbarButtons(['bold', 'italic', 'underline', 'strikeThrough']),
            //     ])
            //     ->action(function (array $data, Atendimento $record): void {
            //         $this->syncDeclaracao($data, $record);
            //     })
            //     ->after(function ($action, Model $record) {
            //         return redirect($this->getResource()::getUrl('edit', ['record' => $record]));
            //     })
            //     ->modalSubmitActionLabel('Gerar'),
        ];
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

    protected function afterSave(): void
    {
        $this->syncDeclaracao([
            'titulo' => 'Declaração de Atendimento',
            'descricao' => $this->getDescription(),
        ], $this->record);
    }

    protected function syncDeclaracao(array $data, Atendimento $record): void
    {
        $path = 'atendimentos/pdfs/'.$record->id.'.pdf';

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

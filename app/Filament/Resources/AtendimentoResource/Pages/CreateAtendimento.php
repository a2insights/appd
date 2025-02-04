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
            'titulo' => 'FICHA DE ATENDIMENTO ASSISTENCIAL',
            'descricao' => $this->getDescription(),
        ], $this->record);

        redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
    }

    protected function getDescription(): string
    {
        $nome = $this->record?->associado?->nome ?? $this->record?->pessoa?->nome;
        $dataNascimento = $this->record->associado?->data_nascimento?->format('d/m/Y');
        $cpf = $this->record?->associado?->cpf ?? $this->record?->pessoa?->cpf;
        $rg = $this->record?->associado?->rg ?? '';
        $tipoDeficiencia = @Str::title($this->record->associado->tipo_deficiencia->value);

        $dataAtendimento = $this->record->created_at->format('d/m/Y');
        $horario = $this->record->created_at->format('H:m');

        $servicosPrestados = $this->record->tipos->pluck('titulo');

        $text = '<b>IDENTIFICAÇÃO DO USUÁRIO:</b><br>';
        $text .= "<b>Nome Completo:</b> {$nome}<br>";
        if ($dataNascimento) {
            $text .= "<b>Data de Nascimento:</b> {$dataNascimento}<br>";
        }

        $text .= ($cpf ? "<b>CPF:</b> {$cpf}" : '').($rg ? " | <b>RG:</b> {$rg}" : '');
        $text .= ! empty($text) ? '<br>' : '';

        if ($tipoDeficiencia) {
            $text .= "<b>Tipo de Deficiência:</b> {$tipoDeficiencia}<br><br>";
        }

        $text .= '<b>DADOS DO ATENDIMENTO:</b><br>';
        $text .= "<b>Data do Atendimento:</b> {$dataAtendimento}<br>";
        $text .= "<b>Horário:</b> {$horario}<br>";

        $text .= '<br><b>SERVIÇOS PRESTADOS:</b><br>';

        $text .= '<ol>';

        foreach ($servicosPrestados as $servico) {
            $text .= sprintf(
                '<li>
                   %s
                </li>',
                htmlspecialchars($servico)
            );
        }

        $text .= '</ol>';

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

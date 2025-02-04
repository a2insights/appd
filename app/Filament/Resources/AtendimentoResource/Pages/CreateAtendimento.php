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

        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
    }

    protected function getDescription(): string
    {
        $nome = $this->record?->associado?->nome ?? $this->record?->pessoa?->nome;
        $dataNascimento = $this->record->associado?->data_nascimento?->format('d/m/Y');
        $cpf = $this->record?->associado?->cpf ?? $this->record?->pessoa?->cpf;
        $rg = $this->record?->associado?->rg ?? '';
        $tipoDeficiencia = @Str::title($this->record->associado->tipo_deficiencia->value);

        $cpf = self::formatDocument($cpf);

        $dataAtendimento = $this->record->created_at->format('d/m/Y');
        $horario = $this->record->created_at->format('H:m');

        $servicosPrestados = $this->record->tipos->pluck('titulo')->all();

        $text = '<b>IDENTIFICAÇÃO DO USUÁRIO:</b><br>';
        $text .= '<b>Nome Completo:</b> '.htmlspecialchars($nome).'<br>';

        if ($dataNascimento) {
            $text .= '<b>Data de Nascimento:</b> '.htmlspecialchars($dataNascimento).'<br>';
        }

        $identificationInfo = [];
        if ($cpf) {
            $identificationInfo[] = '<b>CPF:</b> '.htmlspecialchars($cpf);
        }
        if ($rg) {
            $identificationInfo[] = '<b>RG:</b> '.htmlspecialchars($rg);
        }

        if (! empty($identificationInfo)) {
            $text .= implode(' | ', $identificationInfo).'<br>';
        }

        if ($tipoDeficiencia) {
            $text .= '<b>Tipo de Deficiência:</b> '.htmlspecialchars($tipoDeficiencia).'<br>';
        }

        $text .= '<br><b>DADOS DO ATENDIMENTO:</b><br>';
        $text .= '<b>Data do Atendimento:</b> '.htmlspecialchars($dataAtendimento).'<br>';
        $text .= '<b>Horário:</b> '.htmlspecialchars($horario).'<br>';

        $text .= '<br><b>SERVIÇOS PRESTADOS:</b><br>';
        $servicosList = array_map(
            fn ($servico) => sprintf('<li>%s</li>', htmlspecialchars($servico)),
            $servicosPrestados
        );
        $text .= '<ol>'.implode('', $servicosList).'</ol>';

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

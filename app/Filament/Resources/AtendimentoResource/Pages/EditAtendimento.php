<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Resources\AtendimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAtendimento extends EditRecord
{
    protected static string $resource = AtendimentoResource::class;

    // public function getBreadcrumb(): string
    // {
    //     return 'Em andamento';
    // }

    // public function getTitle(): string
    // {
    //     return $this->record->associado?->nome ?? $this->record->pessoa?->nome ?? 'em atendimento';
    // }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SegmentacaoResource\Pages;

use App\Filament\Resources\SegmentacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSegmentacao extends EditRecord
{
    protected static string $resource = SegmentacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->dispatch('update-segmentacao-preview', filters: $this->record->filters);
    }
}

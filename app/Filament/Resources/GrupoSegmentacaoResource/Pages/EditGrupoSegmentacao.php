<?php

namespace App\Filament\Resources\GrupoSegmentacaoResource\Pages;

use App\Filament\Resources\GrupoSegmentacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGrupoSegmentacao extends EditRecord
{
    protected static string $resource = GrupoSegmentacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

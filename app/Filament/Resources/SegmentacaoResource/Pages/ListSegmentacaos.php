<?php

namespace App\Filament\Resources\SegmentacaoResource\Pages;

use App\Filament\Resources\SegmentacaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSegmentacaos extends ListRecords
{
    protected static string $resource = SegmentacaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

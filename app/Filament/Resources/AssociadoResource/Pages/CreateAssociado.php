<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Resources\AssociadoResource;
use App\Models\Associado;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAssociado extends CreateRecord
{
    protected static string $resource = AssociadoResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar')
                ->icon('heroicon-o-check')
                ->action(fn () => $this->create()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate()
    {
        Associado::noVersioning();
    }
}

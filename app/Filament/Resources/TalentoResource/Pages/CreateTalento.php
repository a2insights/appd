<?php

namespace App\Filament\Resources\TalentoResource\Pages;

use App\Filament\Resources\TalentoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateTalento extends CreateRecord
{
    protected static string $resource = TalentoResource::class;

    protected static bool $canCreateAnother = false;

    public function getTitle(): string|Htmlable
    {
        return 'Adicionar Talento';
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Adicionar Talento')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

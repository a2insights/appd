<?php

namespace App\Filament\Resources\BeneficioResource\Pages;

use App\Filament\Resources\BeneficioResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateBeneficio extends CreateRecord
{
    protected static string $resource = BeneficioResource::class;

    protected static bool $canCreateAnother = false;

    public function getTitle(): string|Htmlable
    {
        return 'Adicionar Beneficio';
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Adicionar Beneficio')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\AtendimentoResource\Pages;

use App\Filament\Resources\AtendimentoResource;
use Filament\Resources\Pages\CreateRecord;

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
}

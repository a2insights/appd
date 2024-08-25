<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Resources\AssociadoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssociado extends CreateRecord
{
    protected static string $resource = AssociadoResource::class;

    protected static bool $canCreateAnother = false;
}

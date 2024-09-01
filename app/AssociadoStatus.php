<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AssociadoStatus: string implements HasColor, HasIcon, HasLabel
{
    case ATIVO = 'ativo';
    case INATIVO = 'inativo';
    case FALECIDO = 'falecido';

    public function getColor(): string
    {
        return match ($this) {
            self::ATIVO => 'success',
            self::INATIVO => 'danger',
            self::FALECIDO => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ATIVO => 'heroicon-o-check-circle',
            self::INATIVO => 'heroicon-o-x-circle',
            self::FALECIDO => 'heroicon-o-exclamation-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ATIVO => 'Ativo',
            self::INATIVO => 'Inativo',
            self::FALECIDO => 'Falecido',
        };
    }
}

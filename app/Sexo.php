<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Sexo: string implements HasColor, HasIcon, HasLabel
{
    case MASCULINO = 'masculino';
    case FEMININO = 'feminino';

    public function getLabel(): string
    {
        return match ($this) {
            self::MASCULINO => 'Masculino',
            self::FEMININO => 'Feminino',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::MASCULINO => 'primary',
            self::FEMININO => 'secondary',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::MASCULINO => 'heroicon-o-user-circle',
            self::FEMININO => 'heroicon-o-user-circle',
        };
    }
}

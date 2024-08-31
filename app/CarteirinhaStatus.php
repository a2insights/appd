<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CarteirinhaStatus: string implements HasColor, HasIcon, HasLabel
{
    case ATIVA = 'ativa';
    case CANCELADA = 'cancelada';
    case VENCIDA = 'vencida';

    public function getColor(): string
    {
        return match ($this) {
            self::ATIVA => 'success',
            self::CANCELADA => 'danger',
            self::VENCIDA => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ATIVA => 'heroicon-o-check-circle',
            self::CANCELADA => 'heroicon-o-x-circle',
            self::VENCIDA => 'heroicon-o-exclamation-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::ATIVA => 'Ativa',
            self::CANCELADA => 'Cancelada',
            self::VENCIDA => 'Vencida',
        };
    }
}

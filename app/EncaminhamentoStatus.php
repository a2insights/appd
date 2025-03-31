<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EncaminhamentoStatus: string implements HasColor, HasIcon, HasLabel
{
    case EM_ANDAMENTO = 'em_andamento';
    case CONTRATADO = 'contratado';

    public function getColor(): string
    {
        return match ($this) {
            self::EM_ANDAMENTO => 'primary',
            self::CONTRATADO => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::EM_ANDAMENTO => 'heroicon-o-play-circle',
            self::CONTRATADO => 'heroicon-o-check-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::EM_ANDAMENTO => 'Em andamento',
            self::CONTRATADO => 'Contratado',
        };
    }
}

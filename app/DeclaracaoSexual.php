<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DeclaracaoSexual: string implements HasColor, HasIcon, HasLabel
{
    case HETEROSSEXUALIDADE = 'heterossexualidade';
    case HOMOSSEXUALIDADE = 'homossexualidade';
    case BISSEXUALIDADE = 'bissexualidade';
    case TRANSSEXUALIDADE = 'transsexualidade';
    case PANSEXUALIDADE = 'pansexualidade';
    case ASSEXUALIDADE = 'assexualidade';
    case INTERGENERO = 'intergenero';

    public function getColor(): string
    {
        return match ($this) {
            self::HETEROSSEXUALIDADE => 'primary',
            self::HOMOSSEXUALIDADE => 'secondary',
            self::BISSEXUALIDADE => 'info',
            self::TRANSSEXUALIDADE => 'warning',
            self::PANSEXUALIDADE => 'success',
            self::ASSEXUALIDADE => 'muted',
            self::INTERGENERO => 'dark',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::HETEROSSEXUALIDADE => 'heroicon-o-user',
            self::HOMOSSEXUALIDADE => 'heroicon-o-users',
            self::BISSEXUALIDADE => 'heroicon-o-heart',
            self::TRANSSEXUALIDADE => 'heroicon-o-adjustments',
            self::PANSEXUALIDADE => 'heroicon-o-sparkles',
            self::ASSEXUALIDADE => 'heroicon-o-ban',
            self::INTERGENERO => 'heroicon-o-globe',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::HETEROSSEXUALIDADE => 'Heterossexualidade',
            self::HOMOSSEXUALIDADE => 'Homossexualidade',
            self::BISSEXUALIDADE => 'Bissexualidade',
            self::TRANSSEXUALIDADE => 'Transsexualidade',
            self::PANSEXUALIDADE => 'Pansexualidade',
            self::ASSEXUALIDADE => 'Assexualidade',
            self::INTERGENERO => 'Intergênero',
        };
    }
}

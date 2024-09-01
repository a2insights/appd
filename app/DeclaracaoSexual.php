<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum DeclaracaoSexual: string implements HasLabel
{
    case HETEROSSEXUALIDADE = 'heterossexualidade';
    case HOMOSSEXUALIDADE = 'homossexualidade';
    case BISSEXUALIDADE = 'bissexualidade';
    case TRANSSEXUALIDADE = 'transsexualidade';
    case PANSEXUALIDADE = 'pansexualidade';
    case ASSEXUALIDADE = 'assexualidade';
    case INTERGENERO = 'intergenero';

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

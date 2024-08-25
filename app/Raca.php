<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum Raca: string implements HasLabel
{
    case BRANCA = 'branca';
    case NEGRA = 'negra';
    case AMARELA = 'amarela';
    case PARDA = 'parda';
    case INDIGENA = 'indigena';
    case IGNORADO = 'ignorado';

    public function getLabel(): string
    {
        return match ($this) {
            self::BRANCA => 'Branca',
            self::NEGRA => 'Negra',
            self::AMARELA => 'Amarela',
            self::PARDA => 'Parda',
            self::INDIGENA => 'Indígena',
            self::IGNORADO => 'Ignorado',
        };
    }
}

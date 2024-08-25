<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum TipoDeficiencia: string implements HasLabel
{
    case VISUAL = 'visual';
    case AUDITIVA = 'auditiva';
    case MENTAL = 'mental';
    case FISICA = 'fisica';
    case MULTIPLA = 'multipla';
    case INTELECTUAL = 'intelectual';

    public function getLabel(): string
    {
        return match ($this) {
            self::VISUAL => 'Visual',
            self::AUDITIVA => 'Auditiva',
            self::MENTAL => 'Mental',
            self::FISICA => 'Física',
            self::MULTIPLA => 'Múltipla',
            self::INTELECTUAL => 'Intelectual',
        };
    }
}

<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum EstadoCivil: string implements HasLabel
{
    case SOLTEIRO = 'solteiro';
    case CASADO = 'casado';
    case DIVORCIADO = 'divorciado';
    case VIUVO = 'viuvo';
    case SEPARADO_JUDICIALMENTE = 'separado_judicialmente';

    public function getLabel(): string
    {
        return match ($this) {
            self::SOLTEIRO => 'Solteiro',
            self::CASADO => 'Casado',
            self::DIVORCIADO => 'Divorciado',
            self::VIUVO => 'Viúvo',
            self::SEPARADO_JUDICIALMENTE => 'Separado Judicialmente',
        };
    }
}

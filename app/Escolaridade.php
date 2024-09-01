<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum Escolaridade: string implements HasLabel
{
    case SEM_ESCOLARIDADE = 'sem_escolaridade';
    case ENSINO_FUNDAMENTAL_INCOMPLETO = 'ensino_fundamental_incompleto';
    case ENSINO_FUNDAMENTAL = 'ensino_fundamental';
    case ENSINO_MEDIO_INCOMPLETO = 'ensino_medio_incompleto';
    case ENSINO_MEDIO = 'ensino_medio';
    case ENSINO_SUPERIOR_INCOMPLETO = 'ensino_superior_incompleto';
    case ENSINO_SUPERIOR = 'ensino_superior';
    case MESTRADO = 'mestrado';
    case DOUTORADO = 'doutorado';

    public function getLabel(): string
    {
        return match ($this) {
            self::SEM_ESCOLARIDADE => 'Sem Escolaridade',
            self::ENSINO_FUNDAMENTAL_INCOMPLETO => 'Ensino Fundamental Incompleto',
            self::ENSINO_FUNDAMENTAL => 'Ensino Fundamental',
            self::ENSINO_MEDIO_INCOMPLETO => 'Ensino Médio Incompleto',
            self::ENSINO_MEDIO => 'Ensino Médio',
            self::ENSINO_SUPERIOR_INCOMPLETO => 'Ensino Superior Incompleto',
            self::ENSINO_SUPERIOR => 'Ensino Superior',
            self::MESTRADO => 'Mestrado',
            self::DOUTORADO => 'Doutorado',
        };
    }
}

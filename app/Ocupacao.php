<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum Ocupacao: string implements HasLabel
{
    case ESTUDANTE = 'estudante';
    case EMPRESARIO = 'empresario';
    case FUNCIONARIO_PUBLICO = 'funcionario_publico';
    case BANCARIO = 'bancario';
    case MILITAR = 'militar';
    case AUTONOMO = 'autonomo';
    case APOSENTADO = 'aposentado';
    case PENSIONISTA = 'pensionista';
    case FUNCIONARIO_PRIVADO = 'funcionario_privado';
    case DONO_DE_CASA = 'dono_de_casa';
    case PROFISSIONAL_LIBERAL = 'profissional_liberal';
    case TRABALHADOR = 'trabalhador';

    public function getLabel(): string
    {
        return match ($this) {
            self::ESTUDANTE => 'Estudante',
            self::EMPRESARIO => 'Empresário',
            self::FUNCIONARIO_PUBLICO => 'Funcionário Público',
            self::BANCARIO => 'Bancário',
            self::MILITAR => 'Militar',
            self::AUTONOMO => 'Autônomo',
            self::APOSENTADO => 'Aposentado',
            self::PENSIONISTA => 'Pensionista',
            self::FUNCIONARIO_PRIVADO => 'Funcionário Privado',
            self::DONO_DE_CASA => 'Dono de Casa',
            self::PROFISSIONAL_LIBERAL => 'Profissional Liberal',
            self::TRABALHADOR => 'Trabalhador',
        };
    }
}

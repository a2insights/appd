<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum OrgaoExpedidorUf: string implements HasLabel
{
    case AC = 'ac';
    case AL = 'al';
    case AP = 'ap';
    case AM = 'am';
    case BA = 'ba';
    case CE = 'ce';
    case DF = 'df';
    case ES = 'es';
    case GO = 'go';
    case MA = 'ma';
    case MT = 'mt';
    case MS = 'ms';
    case MG = 'mg';
    case PA = 'pa';
    case PB = 'pb';
    case PR = 'pr';
    case PE = 'pe';
    case PI = 'pi';
    case RJ = 'rj';
    case RN = 'rn';
    case RS = 'rs';
    case RO = 'ro';
    case RR = 'rr';
    case SC = 'sc';
    case SP = 'sp';
    case SE = 'se';
    case TO = 'to';

    public function getLabel(): string
    {
        return match ($this) {
            self::AC => 'Acre',
            self::AL => 'Alagoas',
            self::AP => 'Amapá',
            self::AM => 'Amazonas',
            self::BA => 'Bahia',
            self::CE => 'Ceará',
            self::DF => 'Distrito Federal',
            self::ES => 'Espírito Santo',
            self::GO => 'Goiás',
            self::MA => 'Maranhão',
            self::MT => 'Mato Grosso',
            self::MS => 'Mato Grosso do Sul',
            self::MG => 'Minas Gerais',
            self::PA => 'Pará',
            self::PB => 'Paraíba',
            self::PR => 'Paraná',
            self::PE => 'Pernambuco',
            self::PI => 'Piauí',
            self::RJ => 'Rio de Janeiro',
            self::RN => 'Rio Grande do Norte',
            self::RS => 'Rio Grande do Sul',
            self::RO => 'Rondônia',
            self::RR => 'Roraima',
            self::SC => 'Santa Catarina',
            self::SP => 'São Paulo',
            self::SE => 'Sergipe',
            self::TO => 'Tocantins',
        };
    }
}

<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum Religiao: string implements HasLabel
{
    case MORMONS = 'mormons';
    case PROTESTANTE = 'protestante';
    case ESPIRITISMO = 'espiritismo';
    case UMBANDA = 'umbanda';
    case BUDISMO = 'budismo';
    case CANDOMBLE = 'candomble';
    case JUDAISMO = 'judaismo';
    case TRADICOES_ESOTERICAS = 'tradicoes_esotericas';
    case ISLAMISMO = 'islamismo';
    case CRENCAS_INDIGENAS = 'crencas_indigenas';
    case CATOLICO = 'catolico';
    case ATEU = 'ateu';
    case OUTRAS = 'outras';

    public function getLabel(): string
    {
        return match ($this) {
            self::MORMONS => 'Mórmons',
            self::PROTESTANTE => 'Protestante',
            self::ESPIRITISMO => 'Espiritismo',
            self::UMBANDA => 'Umbanda',
            self::BUDISMO => 'Budismo',
            self::CANDOMBLE => 'Candomblé',
            self::JUDAISMO => 'Judaísmo',
            self::TRADICOES_ESOTERICAS => 'Tradições Esotéricas',
            self::ISLAMISMO => 'Islamismo',
            self::CRENCAS_INDIGENAS => 'Crenças Indígenas',
            self::CATOLICO => 'Católico',
            self::ATEU => 'Ateu',
            self::OUTRAS => 'Outras',
        };
    }
}

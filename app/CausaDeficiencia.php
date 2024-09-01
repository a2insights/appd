<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum CausaDeficiencia: string implements HasLabel
{
    case ACIDENTE_DE_TRABALHO = 'acidente_de_trabalho';
    case ARTRITE_REUMATOIDE = 'artrite_reumatoide';
    case ACIDENTE_DOMESTICO = 'acidente_domestico';
    case ACIDENTE = 'acidente';
    case POLIOMIELITE = 'poliomielite';
    case CONGENITO = 'congenito';
    case POLIO = 'polio';
    case AVC = 'avc';
    case ACIDENTE_DE_TRANSITO = 'acidente_de_transito';
    case MENINGITE = 'meningite';
    case SEQUELAS_POLIOMIELITE = 'sequelas_poliomielite';
    case PARALISIA_CEREBRAL = 'paralisia_cerebral';
    case HANSENIASE = 'hanseniase';
    case REUMATISMO = 'reumatismo';
    case PCI = 'pci';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACIDENTE_DE_TRABALHO => 'Acidente de Trabalho',
            self::ARTRITE_REUMATOIDE => 'Artrite Reumatoide',
            self::ACIDENTE_DOMESTICO => 'Acidente Doméstico',
            self::ACIDENTE => 'Acidente',
            self::POLIOMIELITE => 'Poliomielite',
            self::CONGENITO => 'Congênito',
            self::POLIO => 'Polio',
            self::AVC => 'AVC',
            self::ACIDENTE_DE_TRANSITO => 'Acidente de Trânsito',
            self::MENINGITE => 'Meningite',
            self::SEQUELAS_POLIOMIELITE => 'Sequelas de Poliomielite',
            self::PARALISIA_CEREBRAL => 'Paralisia Cerebral',
            self::HANSENIASE => 'Hanseníase',
            self::REUMATISMO => 'Reumatismo',
            self::PCI => 'Paralisia Cerebral Infantil (PCI)',
        };
    }
}

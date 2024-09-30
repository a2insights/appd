<?php

namespace App;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CandidatoStatus: string implements HasColor, HasIcon, HasLabel
{
    case NOVO = 'novo';
    case EM_ANDAMENTO = 'em_andamento';
    case SELECIONADO = 'selecionado';
    case DESCLASSIFICADO = 'desclassificado';
    case FINALIZADO = 'finalizado';

    public function getColor(): string
    {
        return match ($this) {
            self::NOVO => 'info',
            self::EM_ANDAMENTO => 'primary',
            self::SELECIONADO => 'success',
            self::DESCLASSIFICADO => 'danger',
            self::FINALIZADO => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::NOVO => 'heroicon-o-information-circle',
            self::EM_ANDAMENTO => 'heroicon-o-play-circle',
            self::SELECIONADO => 'heroicon-o-check-circle',
            self::DESCLASSIFICADO => 'heroicon-o-x-circle',
            self::FINALIZADO => 'heroicon-o-stop-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::NOVO => 'Novo',
            self::EM_ANDAMENTO => 'Em andamento',
            self::SELECIONADO => 'Selecionado',
            self::DESCLASSIFICADO => 'Desclassificado',
            self::FINALIZADO => 'Finalizado',
        };
    }
}

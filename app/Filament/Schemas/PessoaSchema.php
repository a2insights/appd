<?php

namespace App\Filament\Schemas;

use Filament\Forms;
use Filament\Support\RawJs;

class PessoaSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->mask(RawJs::make(<<<'JS'
                        $input.toUpperCase();
                    JS))
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('cpf')
                    ->maxLength(255)
                    ->required()
                    ->unique()
                    ->stripCharacters(['-', '.'])
                    ->mask('999.999.999-99')
                    ->rules(['cpf'])
                    ->live()
                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                        if ($component->getState('cpf') && strlen($component->getState('cpf')) === 14) {
                            $livewire->validateOnly($component->getStatePath());
                        }
                    })
                    ->columnSpan(2),
            ])
                ->columns(5)
                ->columnSpanFull(),
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('telefone_celular')
                    ->tel()
                    ->stripCharacters(['-', '.', '(', ')'])
                    ->placeholder('(DDD) + NÚMERO')
                    ->mask('(99) 99999-9999')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('telefone_whatsapp')
                    ->stripCharacters(['-', '.'])
                    ->tel()
                    ->placeholder('(DDD) + NÚMERO')
                    ->mask('(99) 99999-9999')
                    ->columnSpan(2),
            ])
                ->columns(4)
                ->columnSpanFull(),
        ];
    }
}

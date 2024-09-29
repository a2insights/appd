<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AtendimentoResource\Pages;
use App\Filament\Resources\AtendimentoResource\RelationManagers;
use App\Models\Atendimento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AtendimentoResource extends Resource
{
    protected static ?string $model = Atendimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipo_id')
                    ->relationship('tipo', 'id')
                    ->required(),
                Forms\Components\Select::make('pessoa_id')
                    ->relationship('pessoa', 'id')
                    ->required(),
                Forms\Components\Select::make('associado_id')
                    ->relationship('associado', 'id')
                    ->required(),
                Forms\Components\Toggle::make('em_andamento')
                    ->required(),
                Forms\Components\Toggle::make('finalizado_automaticamente')
                    ->required(),
                Forms\Components\DateTimePicker::make('finalizado_em')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pessoa.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('associado.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('em_andamento')
                    ->boolean(),
                Tables\Columns\IconColumn::make('finalizado_automaticamente')
                    ->boolean(),
                Tables\Columns\TextColumn::make('finalizado_em')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAtendimentos::route('/'),
            'create' => Pages\CreateAtendimento::route('/create'),
            'edit' => Pages\EditAtendimento::route('/{record}/edit'),
        ];
    }
}

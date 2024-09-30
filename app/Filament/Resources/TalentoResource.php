<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalentoResource\Pages;
use App\Models\Talento;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TalentoResource extends Resource
{
    protected static ?string $model = Talento::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Banco de Talentos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('associado_id')
                    ->label('Associado')
                    ->required()
                    ->relationship('associado', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - cpf: {$record->cpf}")
                    ->helperText('Selecione o associado, pesquisando pelo nome ou CPF')
                    ->preload()
                    ->searchable(['nome', 'cpf']),
                CheckboxList::make('cargos')
                    ->label('Competências')
                    ->required()
                    ->relationship(titleAttribute: 'nome')
                    ->columns(4)
                    ->gridDirection('row')
                    ->searchable()
                    ->noSearchResultsMessage('Não foram encontradas competências')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('associado.nome')
                    ->label('Associado')
                    ->url(function (Model $record): ?string {
                        return AssociadoResource::getUrl('edit', ['record' => $record->associado_id]);
                    })
                    ->color('primary'),
                Tables\Columns\TextColumn::make('associado.cpf')
                    ->label('CPF'),
                Tables\Columns\TextColumn::make('associado.telefone_whatsapp')
                    ->label('WhatsApp'),
                Tables\Columns\TextColumn::make('cargos.nome')
                    ->label('Competências')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListTalentos::route('/'),
            'create' => Pages\CreateTalento::route('/create'),
            'edit' => Pages\EditTalento::route('/{record}/edit'),
        ];
    }
}

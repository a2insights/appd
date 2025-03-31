<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VagaResource\Pages;
use App\Filament\Resources\VagaResource\RelationManagers\EncaminhamentosRelationManager;
use App\Models\Vaga;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VagaResource extends Resource
{
    protected static ?string $model = Vaga::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Banco de Talentos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('descricao')
                    ->rows(10)
                    ->cols(20)
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Select::make('empresa_id')
                    ->label('Empresa')
                    ->required()
                    ->relationship('empresa', 'nome')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('ativa')
                    ->label('Ativa')
                    ->default(true)
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('requisitos'),
                CheckboxList::make('cargos')
                    ->label('Cargos')
                    ->required()
                    ->relationship(titleAttribute: 'nome')
                    ->columns(4)
                    ->gridDirection('row')
                    ->searchable()
                    ->noSearchResultsMessage('Não foram encontradas competências')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('inicia_em')
                    ->label('Inicia em')
                    ->displayFormat('d/m/Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->seconds(false)
                    ->locale('pt_BR')
                    ->default(now())
                // ->minDate(now()->subDay())
                // ->maxDate(now()->addMonths(10))
                    ->required()
                    ->columnSpan(1),
                Forms\Components\DateTimePicker::make('finaliza_em')
                    ->label('Finaliza em')
                    ->displayFormat('d/m/Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->seconds(false)
                    ->locale('pt_BR')
                    // ->minDate(now()->subDay())
                    // ->maxDate(now()->addMonths(10))
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ativa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('inicia_em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('finaliza_em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargos.nome')
                    ->label('Cargos')
                    ->badge(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime('d/m/Y H:i')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('cargos')
                    ->relationship('cargos', 'nome')
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            EncaminhamentosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVagas::route('/'),
            'create' => Pages\CreateVaga::route('/create'),
            'edit' => Pages\EditVaga::route('/{record}/edit'),
        ];
    }
}

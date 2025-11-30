<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SegmentacaoResource\Pages;
use App\Filament\Resources\SegmentacaoResource\RelationManagers;
use App\Models\Segmentacao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\NaturalidadeUf;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;

class SegmentacaoResource extends Resource
{
    protected static ?string $model = Segmentacao::class;

    protected static ?string $cluster = \App\Filament\Clusters\Segmentacoes::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Segmentações';

    protected static ?string $modelLabel = 'Segmentação';

    protected static ?string $pluralModelLabel = 'Segmentações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome da Segmentação')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('grupo_segmentacao_id')
                            ->label('Grupo')
                            ->relationship('grupo', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->label('Descrição')
                                    ->maxLength(65535),
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Filtros')
                    ->description('Configure as regras para esta segmentação.')
                    ->statePath('filters')
                    ->schema(\App\Filament\Filters\AssociadoFiltersTable::getFormSchema())
                    ->columns(3),

                Forms\Components\View::make('filament.resources.segmentacao.preview-widget')
                    ->viewData([
                        'initialFilters' => $form->getRecord()?->filters ?? [],
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grupo.name')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSegmentacaos::route('/'),
            'create' => Pages\CreateSegmentacao::route('/create'),
            'edit' => Pages\EditSegmentacao::route('/{record}/edit'),
        ];
    }
}

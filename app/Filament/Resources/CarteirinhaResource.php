<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarteirinhaResource\Pages;
use App\Filament\Schemas\CarteirinhaSchema;
use App\Models\Carteirinha;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CarteirinhaResource extends Resource
{
    protected static ?string $model = Carteirinha::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->extraAttributes(['autocomplete' => false], true)
            // ->disabled(true)
            ->schema(CarteirinhaSchema::schema($form));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('data_emissao')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('foto')
                        ->visibility('private')
                        ->height('auto')
                        ->width('90px')
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('associado.nome')
                            ->url(function (Model $record): ?string {
                                return AssociadoResource::getUrl('edit', ['record' => $record->associado_id]);
                            })
                            ->formatStateUsing(fn (string $state, $record) => $record->associado->abbreviateName())
                            ->color('primary')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('associado.cpf')
                            ->formatStateUsing(fn (string $state, $record) => $record->associado->getDocumento())
                            ->searchable(),
                        Tables\Columns\TextColumn::make('status'),
                        Tables\Columns\TextColumn::make('data_emissao')
                            ->date('d/m/Y')
                            ->color('success')
                            ->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('data_vencimento')
                            ->date('d/m/Y')
                            ->color('danger')
                            ->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('created_at')
                            ->label('Data da emissão')
                            ->date('d/m/Y')
                            ->color('gray')
                            ->weight(FontWeight::Bold),
                    ]),
                ]),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('associados')
                    ->relationship('associado', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - cpf: {$record->cpf}")
                    ->preload()
                    ->multiple(),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_emissao')
                    ->label('Data de Emissão'),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_vencimento')
                    ->label('Data de Vencimento'),
            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->icon(false)
                    ->iconSize(0)
                    ->hiddenLabel(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListCarteirinhas::route('/'),
            'create' => Pages\CreateCarteirinha::route('/create'),
            // 'edit' => Pages\EditCarteirinha::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\CarteirinhaStatus;
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
use Illuminate\Support\Carbon;

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
                    Tables\Columns\ImageColumn::make('foto_url')
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
                            ->formatStateUsing(fn (string $state, $record) => 'Data de emissão: '.Carbon::parse($state)->format('d/m/Y'))
                            ->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('data_vencimento')
                            ->date('d/m/Y')
                            ->color('danger')
                            ->formatStateUsing(fn (string $state, $record) => 'Data de vencimento: '.Carbon::parse($state)->format('d/m/Y'))
                            ->weight(FontWeight::Bold),
                    ]),
                ]),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('associados')
                    ->relationship('associado', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - {$record->getDocumento()}")
                    ->multiple()
                    ->columnSpan(3),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_emissao')
                    ->label('Data de Emissão'),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_vencimento')
                    ->label('Data de Vencimento'),
            ], layout: FiltersLayout::AboveContent)
            ->headerActions([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->disabled(fn ($record) => $record->status === CarteirinhaStatus::VENCIDA || $record->status === CarteirinhaStatus::CANCELADA)
                    ->label('Visualizar'),
                Tables\Actions\Action::make('cancel')
                    ->label('Cancelar')
                    ->disabled(fn ($record) => $record->status === CarteirinhaStatus::CANCELADA || $record->status === CarteirinhaStatus::VENCIDA)
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn ($record) => $record->update(['status' => CarteirinhaStatus::CANCELADA]))
                    ->requiresConfirmation(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->paginationPageOptions([6, 9, 15, 18, 21, 24])
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

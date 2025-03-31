<?php

namespace App\Filament\Resources;

use App\EncaminhamentoStatus;
use App\Filament\Resources\EncaminhamentoResource\Pages;
use App\Models\Encaminhamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EncaminhamentoResource extends Resource
{
    protected static ?string $model = Encaminhamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Banco de Talentos';

    protected static ?int $navigationSort = 3;

    // protected static ?string $navigationParentItem = 'Vagas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(EncaminhamentoStatus::class)
                    ->default(EncaminhamentoStatus::EM_ANDAMENTO)
                    ->required()
                    ->columnSpan(2),

                Forms\Components\Select::make('vaga_id')
                    ->label('Vaga')
                    ->required()
                    ->relationship('vaga', 'titulo')
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('talento_id')
                    ->label('Talento')
                    ->required()
                    ->relationship('talento', 'nome', function ($query) {
                        return $query->join('associados', 'talentos.associado_id', '=', 'associados.id')
                            ->select('talentos.*', 'associados.nome as associado_nome')
                            ->orderBy('associados.nome');
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->associado->nome} - {$record->associado->getDocumento()}")
                    ->preload()
                    ->searchable(),

                \Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField::make('encaminhamento')
                    ->visibility('private')
                    ->hidden(fn (?Model $record) => ! $record?->encaminhamento)
                    ->label('Ficha de Encaminhamento')
                    ->minHeight('80svh')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vaga.titulo')
                    ->label('Vaga')
                    ->numeric()
                    ->url(function (Model $record): ?string {
                        return VagaResource::getUrl('edit', ['record' => $record->vaga_id]);
                    })
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('talento.id')
                    ->label('Talento')
                    ->color('primary')
                    ->url(function (Model $record): ?string {
                        return TalentoResource::getUrl('edit', ['record' => $record->talento_id]);
                    })
                    ->formatStateUsing(fn ($record) => $record->talento->associado->nome)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de cadastro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEncaminhamentos::route('/'),
            'create' => Pages\CreateEncaminhamento::route('/create'),
            'edit' => Pages\EditEncaminhamento::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AtendimentoResource\Pages;
use App\Models\Atendimento;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AtendimentoResource extends Resource
{
    protected static ?string $model = Atendimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?int $navigationSort = 3;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                CheckboxList::make('tipos')
                    ->label('Tipos de Atendimento')
                    ->required()
                    ->helperText('Selecione os tipos de atendimento')
                    ->relationship(titleAttribute: 'titulo', modifyQueryUsing: fn ($query) => $query->orderBy('sort'))
                    ->columns(4)
                    ->gridDirection('row')
                    ->columnSpanFull(),
                Forms\Components\Select::make('associado_id')
                    ->label('Associado')
                    ->relationship('associado', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - {$record->getDocumento()}")
                    ->helperText('Selecione o associado')
                    ->searchable(),
                Forms\Components\Select::make('pessoa_id')
                    ->label('Pessoa')
                    ->relationship('pessoa', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - cpf: {$record->cpf}")
                    ->helperText('Selecione a pessoa')
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('cpf')
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->stripCharacters(['-', '.'])
                            ->mask('999.999.999-99')
                            ->rules(['cpf'])
                            ->live()
                            ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                                $livewire->validateOnly($component->getStatePath());
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('telefone_whatsapp')
                            ->stripCharacters(['-', '.'])
                            ->tel()
                            ->placeholder('(DDD) + NÚMERO')
                            ->mask('(99) 99999-9999')
                            ->columnSpanFull(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Criar Pessoa')
                            ->modalSubmitActionLabel('Criar')
                            ->modalWidth('lg');
                    }),

                \Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField::make('declaracao')
                    ->visibility('private')
                    ->hidden(fn (?Model $record) => ! $record?->declaracao)
                    ->label('Declaração de Atendimento')
                    ->minHeight('80svh')
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
                        return $record->associado_id ? AssociadoResource::getUrl('edit', ['record' => $record->associado_id]) : '#';
                    })
                    ->searchable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('pessoa.nome')
                    ->url(function (Model $record): ?string {
                        return $record->pessoa_id ? PessoaResource::getUrl('edit', ['record' => $record->pessoa_id]) : '#';
                    })
                    ->searchable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('tipos.titulo')
                    ->badge()
                    ->searchable(),
                // Tables\Columns\IconColumn::make('em_andamento')
                //     ->boolean(),
                // Tables\Columns\IconColumn::make('finalizado_automaticamente')
                //     ->boolean(),
                // Tables\Columns\TextColumn::make('finalizado_em')
                //     ->dateTime()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data do Atendimento')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipos')
                    ->relationship('tipos', 'titulo')
                    ->preload()
                    ->multiple(),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at')
                    ->label('Data do Atendimento'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAtendimentos::route('/'),
            'create' => Pages\CreateAtendimento::route('/create'),
            'edit' => Pages\EditAtendimento::route('/{record}/edit'),
        ];
    }
}

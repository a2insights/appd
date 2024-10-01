<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AtendimentoResource\Pages;
use App\Filament\Schemas\AssociadoSchema;
use App\Models\Associado;
use App\Models\Atendimento;
use App\Models\Tipo;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AtendimentoResource extends Resource
{
    protected static ?string $model = Atendimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - cpf: {$record->cpf}")
                    ->helperText('Selecione o associado')
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('pessoa_id')
                    ->label('Pessoa')
                    ->relationship('pessoa', 'nome')
                    ->getOptionLabelFromRecordUsing(fn (Model $record): ?string => "{$record->nome} - cpf: {$record->cpf}")
                    ->helperText('Selecione a pessoa')
                    ->preload()
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
                Wizard::make(fn (array $state, ?Model $record) => self::steps($state, $record, $form))
                    ->columnSpanFull()
                    ->nextAction(
                        fn (Action $action) => $action->label('Editar Associado'),
                    )
                    ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('associado.nome')
                    ->label('Associado')
                    ->url(function (Model $record): ?string {
                        return ! $record->associado_id ?? AssociadoResource::getUrl('edit', ['record' => $record->associado_id]);
                    })
                    ->searchable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('pessoa.nome')
                    ->url(function (Model $record): ?string {
                        return ! $record->pessoa_id ?? PessoaResource::getUrl('edit', ['record' => $record->pessoa_id]);
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAtendimentos::route('/'),
            'create' => Pages\CreateAtendimento::route('/create'),
            'edit' => Pages\EditAtendimento::route('/{record}/edit'),
        ];
    }

    public static function steps(array $state, ?Model $record, $form): array
    {
        return [];

        // TODO: Implementar lógica para exibir os steps
        if (! $record) {
            return [];
        }

        $steps = [];

        $tipos = $record->tipos->map(fn (Tipo $tipo) => Str::slug($tipo->titulo));
        if ($tipos->contains('atualizacao-cadastral')) {
            $steps[] = self::selecionarAssociado();
            $steps[] = self::editarAssociado($form);
        }

        $steps[] = self::finalizarAtendimento();

        return $steps;
    }

    private static function finalizarAtendimento()
    {
        return Wizard\Step::make('Finalizar Atendimento')
            ->description('Finalize o atendimento')
            ->schema([
            ]);
    }

    private static function selecionarAssociado()
    {
        return Wizard\Step::make('Associado')
            ->description('Selecione o associado')
            ->schema([
                Forms\Components\Select::make('associado_id')
                    ->label('Associado')
                    ->relationship('associado', 'nome')
                    ->preload()
                    ->afterStateUpdated(function ($state, $record, Get $get, Set $set) {
                        $associado = Associado::find($state);
                        $set('nome', $associado->nome);

                        return $state;
                    })
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    private static function editarAssociado($form)
    {
        return Wizard\Step::make('Editar Associado')
            ->description('Atualize os dados do associado')
            ->schema(AssociadoSchema::schema(true));
    }
}

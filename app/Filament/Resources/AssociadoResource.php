<?php

namespace App\Filament\Resources;

use App\Filament\Filters\AssociadoFiltersTable;
use App\Filament\Resources\AssociadoResource\Pages;
use App\Filament\Resources\AssociadoResource\RelationManagers\CarteirinhasRelationManager;
use App\Filament\Resources\AssociadoResource\Widgets\AssociadosOverview;
use App\Filament\Schemas\AssociadoSchema;
use App\Models\Associado;
use App\Models\Cid10;
use Facades\App\Services\MunicipioService;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssociadoResource extends Resource
{
    protected static ?string $model = Associado::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'nome';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nome', 'cpf', 'rg'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->nome.' ('.$record->cpf.')';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();

        return $form
            ->extraAttributes(['autocomplete' => false], true)
            ->schema(AssociadoSchema::schema($form, $record));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\ImageColumn::make('foto_url')
                //     ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nome')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->extraAttributes(['autocomplete' => false])
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome_social')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sexo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('declaracao_sexual')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('rg')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('orgao_expedidor')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('orgao_expedidor_uf')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estado_civil')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('certidao_nascimento')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('naturalidade_municipio_ibge')
                    ->label('Naturalidade')
                    ->formatStateUsing(fn (Associado $associado) => self::getMunicipios()->firstWhere('id', $associado->naturalidade_municipio_ibge)->nome)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('naturalidade_uf')
                    ->label('UF')
                    ->formatStateUsing(fn (Associado $associado) => Str::upper($associado->naturalidade_uf->value))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('mae')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pai')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('religiao')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('escolaridade')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('ocupacoes')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('raca')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('crm')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cid10')
                    ->label('Deficiência')
                    ->toggleable(isToggledHiddenByDefault: false)
                   // ->formatStateUsing(fn (Associado $associado, string $state) => $associado->cid10()->get()->where('id', $state)->map(fn (Cid10 $cid10) => $cid10->codigo)->join(', '))
                   // ->tooltip(fn (Associado $associado) => $associado->cid10()->get()->map(fn (Cid10 $cid10) => "{$cid10->codigo} - {$cid10->descricao}")->join(', '))
                    ->badge(),
                Tables\Columns\TextColumn::make('tipo_deficiencia')
                    ->label('Tipo')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('causa_deficiencia')
                    ->label('Causa')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('aparelhos_utilizado')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cep')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric(),
                Tables\Columns\TextColumn::make('rua')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bairro')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('numero')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estado')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cidade')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('perimetro')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telefone_celular')
                    ->label('Celular')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telefone_whatsapp')
                    ->label('WhatsApp')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('telefone_fixo')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(AssociadoFiltersTable::filters(), layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('super_admin')),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            AssociadosOverview::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            CarteirinhasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssociados::route('/'),
            'create' => Pages\CreateAssociado::route('/create'),
            'edit' => Pages\EditAssociado::route('/{record}/edit'),
            'revisions' => Pages\RevisionsAssociado::route('/{record}/revisions'),
        ];
    }

    /*
    * @return Collection<\App\Municipio>
    */
    private static function getMunicipios(): Collection
    {
        return MunicipioService::all();
    }
}

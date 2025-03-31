<?php

namespace App\Filament\Resources\VagaResource\RelationManagers;

use App\EncaminhamentoStatus;
use App\Filament\Resources\TalentoResource;
use App\Models\Talento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EncaminhamentosRelationManager extends RelationManager
{
    protected static string $relationship = 'encaminhamentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('talento_id')
                    ->label('Talento')
                    ->options(
                        Talento::query()
                            ->get()
                            ->mapWithKeys(fn (Talento $talento) => [$talento->id => "{$talento->associado->nome} cpf: {$talento->associado->cpf}"])
                            ->toArray()
                    )
                    ->getSearchResultsUsing(fn (string $search): array => Talento::query()
                        ->whereHas('associado', fn ($query) => $query->where('nome', 'like', "%$search%")
                            ->orWhere('cpf', 'like', "%$search%")
                        )
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn (Talento $talento) => [$talento->id => "{$talento->associado->nome} cpf: {$talento->associado->cpf}"])
                        ->toArray()
                    )
                    ->getOptionLabelsUsing(fn (array $values): array => Talento::whereIn('id', $values)
                        ->get()
                        ->mapWithKeys(fn (Talento $talento) => [$talento->id => "{$talento->associado->nome} cpf: {$talento->associado->cpf}"])
                        ->toArray()
                    )
                    ->searchPrompt('Pesquise talentos pelo nome ou CPF')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(EncaminhamentoStatus::class)
                    ->default(EncaminhamentoStatus::NOVO)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('talento.associado.nome')
                    ->label('Associado')
                    ->url(function (Model $record): ?string {
                        return TalentoResource::getUrl('edit', ['record' => $record->talento_id]);
                    })
                    ->searchable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('talento.cargos.nome')
                    ->label('Competências')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Adicionado em')
                    ->date('d/m/Y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Encaminhamento')
                    ->createAnother(false),
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
}

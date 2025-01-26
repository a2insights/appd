<?php

namespace App\Filament\Resources\AssociadoResource\RelationManagers;

use App\CarteirinhaStatus;
use App\Filament\Schemas\CarteirinhaSchema;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CarteirinhasRelationManager extends RelationManager
{
    protected static string $relationship = 'carteirinhas';

    public function form(Form $form): Form
    {
        $associado = $this->getOwnerRecord();

        return $form->schema(CarteirinhaSchema::schema($form, $associado));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('data_emissao')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('foto_url')
                        ->height('auto')
                        ->grow(false)
                        ->width('90px'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('associado.nome')
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
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->beforeFormValidated(function (CreateAction $action) {
                        $carterinhas = $this->getOwnerRecord()
                            ->carteirinhas()
                            ->whereStatus(CarteirinhaStatus::ATIVA)
                            ->count();
                        if ($carterinhas > 0) {
                            Notification::make()
                                ->inline()
                                ->danger()
                                ->title('Existe uma carteirinha em ativa.')
                                ->body('Não é possível criar uma nova carteirinha enquanto houver uma ativa. Você deve cancelar a carteirinha ativa antes de criar uma nova.')
                                ->send();
                            $action->halt();
                        }
                    })
                    ->createAnother(false)
                    // ->mutateFormDataUsing(fn (ComponentContainer $form, $model) => $form->fill([
                    //     'associado_id' => $this->getOwnerRecord()->id,
                    //     'foto' => $this->getOwnerRecord()->foto,
                    //     'status' => CarteirinhaStatus::ATIVA,
                    //     'data_emissao' => now(),
                    //     'data_vencimento' => now()->addYear(2),
                    // ]))
                    ->using(function (array $data, $model): Model {
                        $associado = $this->getOwnerRecord();

                        if ($associado->foto === $data['foto']) {
                            $filename = basename($data['foto']);
                            $targetPath = 'carteirinhas/fotos/'.uniqid().'_'.$filename;

                            Storage::disk(config('filament.default_filesystem_disk'))
                                ->copy($associado->foto, $targetPath);

                            $data['foto'] = $targetPath;
                        }

                        $data['associado_id'] = $associado->id;

                        $carteirinha = $model::create($data);

                        return $carteirinha;
                    }),
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
            ->paginationPageOptions([3, 6, 9])
            ->defaultSort('id', 'desc');
    }
}

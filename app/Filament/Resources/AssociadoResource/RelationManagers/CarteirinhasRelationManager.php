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
                    Tables\Columns\ImageColumn::make('foto')
                        ->visibility('private')
                        ->height('auto')
                        ->width('90px'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('status')
                            ->sortable(),
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
                            ->date('d/m/Y H:i:s')
                            ->color('gray')
                            ->weight(FontWeight::Bold),
                    ]),
                ]),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 5,
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
                                ->body('Não é possível criar uma nova carteirinha enquanto houver uma ativa.')
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
                            $targetPath = 'carteirinhas/'.uniqid().'_'.$filename;

                            Storage::disk(config('filament.default_filesystem_disk'))
                                ->copy($associado->foto, $targetPath);

                            $data['foto'] = $targetPath;
                        }

                        $data['associado_id'] = $associado->id;

                        return $model::create($data);
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
}

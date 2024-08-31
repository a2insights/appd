<?php

namespace App\Filament\Resources\AssociadoResource\RelationManagers;

use App\CarteirinhaStatus;
use Filament\Forms;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CarteirinhasRelationManager extends RelationManager
{
    protected static string $relationship = 'carteirinhas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->imagePreviewHeight('200')
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->panelLayout('integrated')
                // ->imageEditorAspectRatios([
                //     '1:1',
                //     null,
                // ])
                    ->imageEditorMode(2)
                // ->loadingIndicatorPosition('left')
                // ->panelAspectRatio('1:1')
                // ->panelLayout('integrated')
                // TODO: Not working. Create an PR to fix this in filament
                    ->removeUploadedFileButtonPosition('right')
                // ->uploadButtonPosition('left')
                // ->uploadProgressIndicatorPosition('left')
                    ->required()
                    ->directory('avatars')
                    ->downloadable()
                    ->maxSize(1024)
                    ->image()
                    ->removeUploadedFileButtonPosition('right')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->openable()
                    ->columnSpanFull(),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(CarteirinhaStatus::class)
                    ->default(CarteirinhaStatus::ATIVA)
                    ->required()
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('data_emissao')
                    ->default(now())
                    ->required()
                    ->columnSpan(1),
                Forms\Components\DatePicker::make('data_vencimento')
                    ->default(now()->addYear(2))
                    ->required()
                    ->columnSpan(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('data_emissao')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('foto')
                        ->height('auto')
                        ->width('80px'),
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
                    ->beforeFormFilled(function (CreateAction $action) {
                        $carterinhas = $this->getOwnerRecord()
                            ->carteirinhas()
                            ->whereStatus(CarteirinhaStatus::ATIVA)->count();
                        if ($carterinhas > 0) {
                            Notification::make()
                                ->inline()
                                ->danger()
                                ->title('Existe uma carteirinha em ativa.')
                                ->body('Não é possível criar uma nova carteirinha enquanto houver uma ativa.')
                                ->persistent()
                                ->send();

                            $action->halt();
                        }
                    })
                    ->createAnother(false)
                    ->mountUsing(fn (ComponentContainer $form) => $form->fill([
                        'foto' => $this->getOwnerRecord()->foto,
                        'status' => CarteirinhaStatus::ATIVA,
                        'data_emissao' => now(),
                        'data_vencimento' => now()->addYear(2),
                    ]))
                    ->using(function (array $data, string $model): Model {
                        //

                        return $model::create($data);
                    }),
                // ->afterFormFilled(function (array $data): array {
                //     $data['foto'] = $this->getOwnerRecord()->foto;

                //     return $data;
                // }),
                // ->afterFormFilled(function (Set $set) {
                //     // associado foto
                //     $associado = $this->getOwnerRecord();
                //     $set('foto', $associado->foto);
                // }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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

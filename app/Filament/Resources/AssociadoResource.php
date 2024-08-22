<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssociadoResource\Pages;
use App\Filament\Resources\AssociadoResource\RelationManagers;
use App\Models\Associado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssociadoResource extends Resource
{
    protected static ?string $model = Associado::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('foto')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->required(),
                Forms\Components\TextInput::make('nome_social')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sexo')
                    ->required(),
                Forms\Components\TextInput::make('declaracao_sexual'),
                Forms\Components\TextInput::make('cpf')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rg')
                    ->maxLength(255),
                Forms\Components\TextInput::make('orgao_expedidor'),
                Forms\Components\TextInput::make('orgao_expedidor_uf'),
                Forms\Components\TextInput::make('estado_civil')
                    ->required(),
                Forms\Components\TextInput::make('certidao_nascimento')
                    ->maxLength(255),
                Forms\Components\TextInput::make('naturalidade_ibge')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mae')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pai')
                    ->maxLength(255),
                Forms\Components\TextInput::make('religiao')
                    ->required(),
                Forms\Components\TextInput::make('escolaridade')
                    ->required(),
                Forms\Components\TextInput::make('raca')
                    ->required(),
                Forms\Components\TextInput::make('cid10')
                    ->required(),
                Forms\Components\TextInput::make('crm')
                    ->maxLength(255),
                Forms\Components\TextInput::make('causa_deficiencia')
                    ->required(),
                Forms\Components\TextInput::make('tipo_deficiencia')
                    ->required(),
                Forms\Components\TextInput::make('aparelho_utilizado'),
                Forms\Components\TextInput::make('cep')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ocupacao'),
                Forms\Components\TextInput::make('rua')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bairro')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cidade')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('perimetro')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefone_celular')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefone_whatsapbigintp')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefone_fixo')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('foto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nome_social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sexo'),
                Tables\Columns\TextColumn::make('declaracao_sexual'),
                Tables\Columns\TextColumn::make('cpf')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orgao_expedidor'),
                Tables\Columns\TextColumn::make('orgao_expedidor_uf'),
                Tables\Columns\TextColumn::make('estado_civil'),
                Tables\Columns\TextColumn::make('certidao_nascimento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('naturalidade_ibge')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mae')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('religiao'),
                Tables\Columns\TextColumn::make('escolaridade'),
                Tables\Columns\TextColumn::make('raca'),
                Tables\Columns\TextColumn::make('crm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('causa_deficiencia'),
                Tables\Columns\TextColumn::make('tipo_deficiencia'),
                Tables\Columns\TextColumn::make('aparelho_utilizado'),
                Tables\Columns\TextColumn::make('cep')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ocupacao'),
                Tables\Columns\TextColumn::make('rua')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bairro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('perimetro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone_celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone_whatsapbigintp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone_fixo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAssociados::route('/'),
            'create' => Pages\CreateAssociado::route('/create'),
            'edit' => Pages\EditAssociado::route('/{record}/edit'),
        ];
    }
}

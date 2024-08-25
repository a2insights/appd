<?php

namespace App\Filament\Resources;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\Filament\Resources\AssociadoResource\Pages;
use App\Models\Associado;
use App\Models\Cid10;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\OrgaoExpedidor;
use App\OrgaoExpedidorUf;
use App\Raca;
use App\Religiao;
use App\TipoDeficiencia;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AssociadoResource extends Resource
{
    protected static ?string $model = Associado::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->required()
                    ->image()
                    ->columnSpanFull(),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('nome')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),
                    Forms\Components\ToggleButtons::make('status')
                        ->inline()
                        ->options(AssociadoStatus::class)
                        ->default(AssociadoStatus::ATIVO)
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\DatePicker::make('data_nascimento')
                        ->required()
                        ->columnSpan(1),
                ])
                    ->columnSpanFull()
                    ->columns(7),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('nome_social')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(5),
                    Forms\Components\Radio::make('sexo')
                        ->options([
                            'masculino' => 'Masculino',
                            'feminino' => 'Feminino',
                        ])
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\Select::make('declaracao_sexual')
                        ->options(DeclaracaoSexual::class)
                        ->columnSpan(2),
                ])
                    ->columnSpanFull()
                    ->columns(8),

                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('cpf')
                        ->maxLength(255)
                        ->columnSpan(2)
                        ->mask('999.999.999-99')
                        ->rules([
                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) {
                                $cpf = preg_replace('/[^0-9]/is', '', $value);

                                if (strlen($cpf) == 11) {
                                    if (preg_match('/(\d)\1{10}/', $cpf)) {
                                        $fail('CPF inválido.');
                                    }

                                    for ($t = 9; $t < 11; $t++) {
                                        for ($d = 0, $c = 0; $c < $t; $c++) {
                                            $d += $cpf[$c] * (($t + 1) - $c);
                                        }
                                        $d = ((10 * $d) % 11) % 10;
                                        if ($cpf[$c] != $d) {
                                            $fail('CPF inválido.');
                                        }
                                    }
                                }
                            },
                        ])
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                            $livewire->validateOnly($component->getStatePath());
                        }),
                    Forms\Components\TextInput::make('rg')
                        ->maxLength(255)
                        ->columnSpan(2),
                    Forms\Components\Select::make('orgao_expedidor')
                        ->options(OrgaoExpedidor::class)
                        ->default(OrgaoExpedidor::PC)
                        ->searchable()
                        ->columnSpan(4),
                    Forms\Components\Select::make('orgao_expedidor_uf')
                        ->options(OrgaoExpedidorUf::class)
                        ->searchable()
                        ->default(OrgaoExpedidorUf::PA)
                        ->columnSpan(2),
                ])
                    ->columnSpanFull()
                    ->columns(10),
                Forms\Components\Group::make([
                    Forms\Components\Select::make('estado_civil')
                        ->required()
                        ->options(EstadoCivil::class)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('certidao_nascimento')
                        ->maxLength(255)
                        ->columnSpan(3),
                    Forms\Components\Select::make('naturalidade_uf')
                        ->required()
                        ->options(NaturalidadeUf::class)
                        ->afterStateUpdated(function (Set $set) {
                            $set('naturalidade_municipio_ibge', null);
                        })
                        ->live()
                        ->searchable()
                        ->default(NaturalidadeUf::PA)
                        ->columnSpan(2),
                    Forms\Components\Select::make('naturalidade_municipio_ibge')
                        ->required()
                        ->options(fn (Get $get): array => self::getMunicipioByUf($get('naturalidade_uf')))
                        ->searchable()
                        ->columnSpan(3),
                ])
                    ->columnSpanFull()
                    ->columns(10),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('mae')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(3),
                    Forms\Components\TextInput::make('pai')
                        ->maxLength(255)
                        ->columnSpan(3),
                ])
                    ->columnSpanFull()
                    ->columns(6),
                Forms\Components\Group::make([
                    Forms\Components\Select::make('religiao')
                        ->required()
                        ->options(Religiao::class)
                        ->columnSpan(2),
                    Forms\Components\Select::make('ocupacao')
                        ->options(Ocupacao::class)
                        ->multiple()
                        ->columnSpan(2),
                    Forms\Components\Select::make('escolaridade')
                        ->required()
                        ->options(Escolaridade::class)
                        ->columnSpan(2),
                    Forms\Components\Select::make('raca')
                        ->required()
                        ->options(Raca::class)
                        ->columnSpan(2),
                ])
                    ->columnSpanFull()
                    ->columns(8),
                Forms\Components\Select::make('cid10')
                    ->relationship(titleAttribute: 'codigo')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn (Cid10 $record) => "{$record->codigo} - {$record->descricao}")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('crm')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\Select::make('causa_deficiencia')
                        ->required()
                        ->options(CausaDeficiencia::class)
                        ->columnSpan(2),
                    Forms\Components\Select::make('tipo_deficiencia')
                        ->required()
                        ->options(TipoDeficiencia::class)
                        ->columnSpan(2),
                    Forms\Components\Select::make('aparelho_utilizado')
                        ->required()
                        ->options(AparelhoUtilizado::class)
                        ->columnSpan(2),
                ])
                    ->columnSpanFull()
                    ->columns(7),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('cep')
                        ->required()
                        ->mask('99999-999')
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component, Set $set) {
                            $value = $component->getState('cep');

                            $cep = preg_replace('/[^0-9]/is', '', $value);

                            if (strlen($cep) === 8) {
                                $cepData = self::getCepAddress($cep);

                                if (isset($cepData['message'])) {
                                    Notification::make()
                                        ->title('CEP não encontrado')
                                        ->danger()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title($cepData['street'])
                                        ->danger()
                                        ->send();
                                    $set('rua', $cepData['street'] ?? null);
                                    $set('bairro', $cepData['neighborhood'] ?? null);
                                    $set('cidade', $cepData['city'] ?? null);
                                    $set('estado', $cepData['state'] ?? null);
                                }
                            }
                        })
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('rua')
                        ->required()
                        ->disabled()
                        ->maxLength(255)
                        ->columnSpan(4),
                    Forms\Components\TextInput::make('bairro')
                        ->required()
                        ->disabled()
                        ->maxLength(255)
                        ->columnSpan(3),
                    Forms\Components\TextInput::make('numero')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                ])
                    ->columnSpanFull()
                    ->columns(10),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('estado')
                        ->required()
                        ->disabled()
                        ->maxLength(255)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('cidade')
                        ->required()
                        ->disabled()
                        ->maxLength(255)
                        ->columnSpan(3),
                    Forms\Components\TextInput::make('perimetro')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(5),
                ])
                    ->columnSpanFull()
                    ->columns(10),
                Forms\Components\Group::make([
                    PhoneInput::make('telefone_celular')
                        ->defaultCountry('BR')
                        ->validateFor(
                            lenient: true,
                        )
                        ->columnSpan(2),
                    PhoneInput::make('telefone_whatsap')
                        ->defaultCountry('BR')
                        ->validateFor(
                            lenient: true,
                        )
                        ->columnSpan(2),
                    PhoneInput::make('telefone_fixo')
                        ->defaultCountry('BR')
                        ->validateFor(
                            lenient: true,
                        )
                        ->placeholderNumberType('FIXED_LINE')
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->columnSpan(4),
                ])
                    ->columnSpanFull()
                    ->columns(10),
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
                Tables\Columns\TextColumn::make('telefone_whatsapp')
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

    private static function getMunicipioByUf(?NaturalidadeUf $uf)
    {
        if (! $uf) {
            return [];
        }

        $url = "https://brasilapi.com.br/api/ibge/municipios/v1/{$uf->value}?providers=dados-abertos-br,gov,wikipedia";

        return cache()->remember("municipios-{$uf->value}", now()->addDay(), function () use ($url) {
            return Http::get($url)->collect()
                ->mapWithKeys(fn ($item) => [$item['codigo_ibge'] => $item['nome']])
                ->all();
        });
    }

    private static function getCepAddress(?string $cep)
    {
        return Http::get('https://brasilapi.com.br/api/cep/v1/'.$cep)->json();
    }
}

<?php

namespace App\Filament\Resources;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\Filament\Resources\AssociadoResource\Pages;
use App\Filament\Resources\AssociadoResource\RelationManagers\CarteirinhasRelationManager;
use App\Filament\Resources\AssociadoResource\Widgets\AssociadosOverview;
use App\Models\Associado;
use App\Models\Cid10;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\OrgaoExpedidor;
use App\OrgaoExpedidorUf;
use App\Raca;
use App\Religiao;
use App\Sexo;
use App\TipoDeficiencia;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AssociadoResource extends Resource
{
    protected static ?string $model = Associado::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

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

    public static function form(Form $form): Form
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
                        ->maxLength(255)
                        ->columnSpan(5),
                    Forms\Components\Radio::make('sexo')
                        ->options(Sexo::class)
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
                        ->rules(['cpf'])
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
                        ->options(fn (Get $get): array => self::getMunicipiosByUf($get('naturalidade_uf')))
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
                    Forms\Components\Select::make('ocupacoes')
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
                Forms\Components\Group::make([
                    Forms\Components\Select::make('cid10')
                        ->relationship(titleAttribute: 'codigo')
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(fn (Cid10 $record) => "{$record->codigo} - {$record->descricao}")
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\Select::make('beneficios')
                        ->relationship(titleAttribute: 'nome')
                        ->multiple()
                        ->preload()
                        ->columnSpan(1),
                ])
                    ->columns(3)
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
                    Forms\Components\Select::make('aparelhos_utilizado')
                        ->options(AparelhoUtilizado::class)
                        ->multiple()
                        ->columnSpan(2),
                ])
                    ->columnSpanFull()
                    ->columns(7),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('cep')
                        ->numeric()
                        ->stripCharacters(['-', '.'])
                        ->required()
                        ->mask('99999-999')
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component, Set $set, Get $get) {
                            $value = $component->getState('cep');
                            // if is editing and cep is not changed

                            if ($value === $get('cep')) {
                                // return;
                            }

                            $cep = preg_replace('/[^0-9]/is', '', $value);

                            if (strlen($cep) === 8) {
                                $cepData = self::getCepAddress($cep);

                                if (isset($cepData['message'])) {
                                    Notification::make()
                                        ->title('CEP não encontrado')
                                        ->danger()
                                        ->send();
                                }

                                $set('rua', $cepData['street'] ?? null);
                                $set('bairro', $cepData['neighborhood'] ?? null);
                                $set('cidade', $cepData['city'] ?? null);
                                $set('estado', $cepData['state'] ?? null);
                            }
                        })
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('rua')
                        ->required()
                        ->disabled()
                        ->dehydrated()
                        ->maxLength(255)
                        ->columnSpan(4),
                    Forms\Components\TextInput::make('bairro')
                        ->required()
                        ->disabled()
                        ->dehydrated()
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
                        ->dehydrated()
                        ->maxLength(255)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('cidade')
                        ->required()
                        ->disabled()
                        ->dehydrated()
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
                SpatieMediaLibraryFileUpload::make('arquivos')
                    ->multiple()
                    ->reorderable()
                    ->preserveFilenames()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->openable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->state(fn (Associado $associado) => $associado->foto ?? 'https://ui-avatars.com/api/?name='.Str::slug($associado->nome, '+').'&size=64&rounded=true&bold=true&color=fff&background=7F9CF5'),
                Tables\Columns\TextColumn::make('nome')
                    ->toggleable(isToggledHiddenByDefault: false)
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
                    ->formatStateUsing(fn (Associado $associado) => self::getMunicipios()->firstWhere('id', $associado->naturalidade_municipio_ibge)['nome'])
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
                Tables\Columns\TextColumn::make('cid10.codigo')
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(AssociadoStatus::class)
                    ->multiple(),
                SelectFilter::make('aniversariantes')
                    ->attribute('data_nascimento')
                    ->multiple()
                    ->options([
                        1 => 'Janeiro',
                        2 => 'Fevereiro',
                        3 => 'Março',
                        4 => 'Abril',
                        5 => 'Maio',
                        6 => 'Junho',
                        7 => 'Julho',
                        8 => 'Agosto',
                        9 => 'Setembro',
                        10 => 'Outubro',
                        11 => 'Novembro',
                        12 => 'Dezembro',
                    ])
                    ->query(function ($query, array $data) {
                        if (! isset($data['values']) || count($data['values']) === 0) {
                            return;
                        }

                        $query->whereRaw('MONTH(data_nascimento) IN ('.implode(',', $data['values']).')');
                    }),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('data_nascimento'),
                SelectFilter::make('sexo')
                    ->options(Sexo::class),
                SelectFilter::make('declaracao_sexual')
                    ->options(DeclaracaoSexual::class)
                    ->multiple(),
                // SelectFilter::make('orgao_expedidor')
                //     ->options(OrgaoExpedidor::class)
                //     ->multiple(),
                // SelectFilter::make('orgao_expedidor_uf')
                //     ->options(OrgaoExpedidorUf::class)
                //     ->multiple(),
                SelectFilter::make('estado_civil')
                    ->options(EstadoCivil::class)
                    ->multiple(),
                SelectFilter::make('naturalidade_uf')
                    ->options(NaturalidadeUf::class)
                    ->multiple(),
                // SelectFilter::make('naturalidade_municipio_ibge')
                //     ->options(fn (): array => self::getMunicipios()->pluck('nome', 'id')->all())
                //     ->query(fn (Get $get): array => self::getMunicipiosByUf($get('naturalidade_uf')))
                //     ->multiple(),
                SelectFilter::make('religiao')
                    ->options(Religiao::class)
                    ->multiple(),
                SelectFilter::make('tipo_deficiencia')
                    ->options(TipoDeficiencia::class)
                    ->multiple(),
                SelectFilter::make('causa_deficiencia')
                    ->options(CausaDeficiencia::class)
                    ->multiple(),
                SelectFilter::make('escolaridade')
                    ->options(Escolaridade::class)
                    ->multiple(),
                SelectFilter::make('raca')
                    ->options(Raca::class)
                    ->multiple(),
                SelectFilter::make('aparelhos_utilizado')
                    ->options(AparelhoUtilizado::class)
                    ->multiple(),
                SelectFilter::make('beneficios')
                    ->relationship('beneficios', 'nome')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('cid10')
                    ->relationship('cid10', 'codigo')
                    ->multiple(),
                SelectFilter::make('ocupacoes')
                    ->options(Ocupacao::class)
                    ->multiple(),
                SelectFilter::make('cidade')
                    ->options(fn (): array => self::getMunicipios()->mapWithKeys(fn ($item) => [$item['nome'] => $item['nome']])->all())
                    ->query(function ($query, array $data) {
                        if (! isset($data['values']) || count($data['values']) === 0) {
                            return;
                        }

                        $query->whereIn('cidade', $data['values']);
                    })
                    ->multiple(),
                // SelectFilter::make('bairro')
                //     ->multiple()
                //     ->options(fn (): array => self::getBairros()->all())
                //     ->query(function ($query, array $data) {
                //         if (! $data['values']) {
                //             return;
                //         }

                //         $query->whereIn('bairro', $data['values']);
                //     }),
                \Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter::make('created_at'),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->actions([
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
        ];
    }

    private static function getMunicipiosByUf($uf)
    {
        $uf = $uf->value ?? $uf;
        if (! $uf) {
            return [];
        }

        $url = "https://brasilapi.com.br/api/ibge/municipios/v1/{$uf}?providers=dados-abertos-br,gov,wikipedia";

        return cache()->remember("municipios-{$uf}", now()->addDay(), function () use ($url) {
            return Http::get($url)->collect()
                ->mapWithKeys(fn ($item) => [$item['codigo_ibge'] => $item['nome']])
                ->all();
        });
    }

    private static function getCepAddress(?string $cep)
    {
        return Http::get('https://brasilapi.com.br/api/cep/v1/'.$cep)->json();
    }

    private static function getMunicipios(): Collection
    {
        // https://servicodados.ibge.gov.br/api/v1/localidades/municipios
        return cache()->rememberForever('municipios', function () {
            $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

            return $response->collect();
        });
    }

    private static function getBairros(): Collection
    {
        return cache()->remember('bairros', now()->addDay(), function () {
            return Associado::query()
                ->select('bairro')
                ->distinct('bairro')
                ->get()
                ->mapWithKeys(fn ($item) => [$item['bairro'] => $item['bairro']]);
        });
    }
}

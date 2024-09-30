<?php

namespace App\Filament\Schemas;

use App\AparelhoUtilizado;
use App\AssociadoStatus;
use App\CausaDeficiencia;
use App\DeclaracaoSexual;
use App\Escolaridade;
use App\EstadoCivil;
use App\Models\Cid10;
use App\NaturalidadeUf;
use App\Ocupacao;
use App\OrgaoExpedidor;
use App\OrgaoExpedidorUf;
use App\Raca;
use App\Religiao;
use App\Sexo;
use App\TipoDeficiencia;
use Facades\App\Services\MunicipioService;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class AssociadoSchema
{
    public static function schema($outResource = false): array
    {
        $fields = [
            \DiscoveryDesign\FilamentGaze\Forms\Components\GazeBanner::make()
                ->lock()
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('foto')
                ->imagePreviewHeight('200')
                // ->panelAspectRatio('2:1')
                // ->panelLayout('integrated')
                ->imageEditor()
                ->visibility('private')
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('3:4')
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
                    ->mask(RawJs::make(<<<'JS'
                        $input.toUpperCase();
                    JS))
                    ->maxLength(255)
                    ->columnSpan(4),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(AssociadoStatus::class)
                    ->default(AssociadoStatus::ATIVO)
                    ->required()
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->displayFormat('d/m/Y')
                    ->format('Y-m-d')
                    ->seconds(false)
                    ->locale('pt_BR')
                    ->minDate(now()->subYears(90))
                    ->maxDate(now()->subMonths(2))
                    ->required()
                    ->columnSpan(1),
            ])
                ->columnSpanFull()
                ->columns(7),
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('nome_social')
                    ->maxLength(255)
                    ->mask(RawJs::make(<<<'JS'
                        $input.toUpperCase();
                    JS))
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
                    ->stripCharacters(['-', '.'])
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
                    ->mask(RawJs::make(<<<'JS'
                        $input.toUpperCase();
                    JS))
                    ->maxLength(255)
                    ->columnSpan(3),
                Forms\Components\TextInput::make('pai')
                    ->mask(RawJs::make(<<<'JS'
                        $input.toUpperCase();
                    JS))
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
                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component, Set $set, Get $get, $record) {
                        $state = (int) preg_replace('/[^0-9]/is', '', $component->getState('cep'));
                        $oldState = (int) preg_replace('/[^0-9]/is', '', $component->getOldState('cep'));

                        $isEditing = ! is_null($record);

                        if ($isEditing && $state === $oldState) {
                            return;
                        }

                        if (strlen((string) $state) === 8 && $state !== $oldState) {
                            $cepData = self::getCepAddress($state);
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
                Forms\Components\TextInput::make('telefone_celular')
                    ->tel()
                    ->stripCharacters(['-', '.', '(', ')'])
                    ->placeholder('(DDD) + NÚMERO')
                    ->mask('(99) 99999-9999')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('telefone_whatsapp')
                    ->stripCharacters(['-', '.'])
                    ->tel()
                    ->placeholder('(DDD) + NÚMERO')
                    ->mask('(99) 99999-9999')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('telefone_fixo')
                    ->tel()
                    ->stripCharacters(['-', '.'])
                    ->placeholder('(DDD) + NÚMERO')
                    ->mask('(99) 9999-9999')
                    ->columnSpan(2),
                // PhoneInput::make('telefone_celular')
                //     ->disallowDropdown()
                //     ->defaultCountry('BR')
                //     ->validateFor(
                //         lenient: true,
                //     )
                //     ->columnSpan(2),
                // PhoneInput::make('telefone_whatsap')
                //     ->disallowDropdown()
                //     ->defaultCountry('BR')
                //     ->validateFor(
                //         lenient: true,
                //     )
                //     ->columnSpan(2),
                // PhoneInput::make('telefone_fixo')
                //     ->defaultCountry('br')
                //     ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                //     ->inputNumberFormat(PhoneInputNumberType::NATIONAL)
                //     ->formatOnDisplay(true)
                //     ->formatAsYouType(true)
                //     ->disableIpLookUp()
                //     ->disallowDropdown()
                //     ->placeholderNumberType('FIXED_LINE')
                //     ->autoPlaceholder('aggressive')
                //     ->validateFor(
                //         country: 'br',
                //         lenient: false,
                //     )
                //     ->placeholderNumberType('FIXED_LINE')
                //     ->columnSpan(2),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->columnSpan(4),
            ])
                ->columnSpanFull()
                ->columns(10),
        ];

        if (! $outResource) {
            $fields[] = SpatieMediaLibraryFileUpload::make('arquivos')
                ->multiple()
                ->maxSize(2048)
                ->reorderable()
                ->visibility('private')
                ->preserveFilenames()
                ->downloadable()
                ->panelLayout('grid')
                ->openable()
                ->columnSpanFull();
        }

        return $fields;
    }

    /*
    * @return Collection<\App\Municipio>
    */
    private static function getMunicipiosByUf($uf)
    {
        $uf = $uf->value ?? $uf;
        if (! $uf) {
            return [];
        }

        return MunicipioService::all()
            ->filter(fn ($item) => $item->uf === $uf)
            ->mapWithKeys(fn ($item) => [$item->codigoIbge => $item->nome])
            ->all();
    }

    private static function getCepAddress(?string $cep)
    {
        return Http::get('https://brasilapi.com.br/api/cep/v1/'.$cep)->json();
    }

    /*
    * @return Collection<\App\Municipio>
    */
    private static function getMunicipios(): Collection
    {
        return MunicipioService::all();
    }

    // private static function getBairros(): Collection
    // {
    //     return cache()->remember('bairros', now()->addDay(), function () {
    //         return Associado::query()
    //             ->select('bairro')
    //             ->distinct('bairro')
    //             ->get()
    //             ->mapWithKeys(fn ($item) => [$item['bairro'] => $item['bairro']]);
    //     });
    // }
}

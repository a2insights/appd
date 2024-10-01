<?php

namespace App\Filament\Schemas;

use App\CarteirinhaStatus;
use App\Filament\Components\PdfViewerField;
use Filament\Forms;
use Filament\Forms\Form;

class CarteirinhaSchema
{
    public static function schema(?Form $form, $associado = null): array
    {
        return [
            // \DiscoveryDesign\FilamentGaze\Forms\Components\GazeBanner::make()
            //     ->lock()
            //     ->columnSpanFull(),
            Forms\Components\FileUpload::make('pdf')
                ->hidden(),
            Forms\Components\FileUpload::make('foto')
                ->hiddenOn('view')
                ->default($associado?->foto)
                ->imagePreviewHeight('200')
                ->maxSize(1024)
                ->imageEditor()
                ->imageResizeMode('cover')
                ->visibility('private')
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
                ->directory('carteirinhas')
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
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required()
                ->columnSpan(1),
            Forms\Components\DatePicker::make('data_vencimento')
                ->default(now()->addYear(2))
                ->native(false)
                ->displayFormat('d/m/Y')
                ->required()
                ->columnSpan(1),
            PdfViewerField::make('pdf')
                ->label('Carteirinha')
                ->visibility('private')
                ->minHeight('41svh')
                ->columnSpanFull(),
        ];
    }
}

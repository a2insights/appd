<?php

namespace App\Providers\Filament;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class FilamentDocumentationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Adicionar item de documentação no menu de usuário (topbar)
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): string => view('filament.components.documentation-button')->render()
        );
    }
}

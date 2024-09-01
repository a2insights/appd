<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AppdInfoWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'widgets.appd-info-widget';
}

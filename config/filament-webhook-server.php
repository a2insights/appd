<?php

return [
    /*
     *  Models that you want to be part of the webhooks options
     */
    'models' => [
        \App\Models\User::class,
        \Cog\Laravel\Ban\Models\Ban::class,
        \AshAllenDesign\ShortURL\Models\ShortURL::class,
    ],
    /*
     */
    'polling' => '10s',
    'webhook' => [
        'keep_history' => true,
    ],
    'pages' => [
        \A2Insights\FilamentSaas\Webhook\Filament\Pages\Webhooks::class,
        \A2Insights\FilamentSaas\Webhook\Filament\Pages\WebhookHistory::class,
    ],
];

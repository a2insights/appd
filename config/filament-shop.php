<?php

return [
    'tenant_scope' => true,
    'navigation_group' => false,
    'products' => [
        'model' => \App\Models\Product::class,
    ],
    'brands' => [
        'model' => \App\Models\Brand::class,
    ],
    'categories' => [
        'model' => \App\Models\Category::class,
    ],

    'decimal_separator' => ',',
    'thousand_separator' => '.',
    'currency' => 'BRL',
    'decimal_precision' => 2,
];

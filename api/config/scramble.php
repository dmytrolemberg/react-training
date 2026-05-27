<?php

declare(strict_types = 1);

return [
    'api_path' => 'api/v1',
    'api_domain' => null,
    'export_path' => 'docs/api.json',
    'info' => [
        'version' => env('API_VERSION', '1.0.0'),
        'description' => 'North Shop client API for catalog browsing, authenticated cart, checkout, orders, profile, wishlist, and reviews.',
    ],
    'ui' => [
        'title' => 'North Shop Client API',
        'theme' => 'light',
        'hide_try_it' => false,
        'hide_schemas' => false,
        'logo' => '',
        'try_it_credentials_policy' => 'include',
        'layout' => 'responsive',
    ],
    'servers' => null,
    'enum_cases_description_strategy' => 'description',
    'enum_cases_names_strategy' => false,
    'flatten_deep_query_parameters' => true,
    'middleware' => ['web'],
    'extensions' => [],
];

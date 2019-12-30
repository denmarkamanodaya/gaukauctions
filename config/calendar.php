<?php


return [
    'google_api_key' => env('GOOGLE_API_KEY', 'AIzaSyAOv3tffAMN-qxxGS0ohoaSeNF_2n8srks'),

    'categoryClass' => '\\App\\Models\\DealerCategories',

    'categoryPivotName' => 'calendar_dealer_categories',

    'members_routes' => true,

    'events' => [
        'Site' => 'Green',
        'Dealers' => 'Blue',
    ],

    'events_legend' => [
        'Site' => 'Site Events',
        'Dealers' => 'Auctions',
    ],

    'default_types' => [
        'App\Models\Dealers' => null
    ],

    'use_categories' => true,
    'category_slugs' => ['antiques--collectables', 'general', 'police--government', 'property--real-estate'],
    'userEvent' => true
];
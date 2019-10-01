<?php

return [
    '__name' => 'api-venue-category',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/api-venue-category.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/api-venue-category' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'venue' => NULL
            ],
            [
                'venue-category' => NULL
            ],
            [
                'lib-app' => NULL
            ],
            [
                'api' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'ApiVenueCategory\\Controller' => [
                'type' => 'file',
                'base' => 'modules/api-venue-category/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'api' => [
            'apiVenueCategoryIndex' => [
                'path' => [
                    'value' => '/venue/category'
                ],
                'handler' => 'ApiVenueCategory\\Controller\\Category::index',
                'method' => 'GET'
            ],
            'apiVenueCategorySingle' => [
                'path' => [
                    'value' => '/venue/category/(:identity)',
                    'params' => [
                        'identity' => 'any'
                    ]
                ],
                'handler' => 'ApiVenueCategory\\Controller\\Category::single',
                'method' => 'GET'
            ],
            'apiVenueCategoryVenue' => [
                'path' => [
                    'value' => '/venue/category/(:identity)/venue'
                ],
                'handler' => 'ApiVenueCategory\\Controller\\Category::venue',
                'method' => 'GET'
            ]
        ]
    ]
];
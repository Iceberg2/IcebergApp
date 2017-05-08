<?php

return [
    'env_path' => __DIR__ . '/../../',
    'env_file' => '.env',
    'providers' => [
        \App\Provider\ExampleProvider::class
    ],
    'services' => [
        'example-service' => \App\Service\Example::class
    ],
    'example-service' => [
    //Example service configurations
    ]
];

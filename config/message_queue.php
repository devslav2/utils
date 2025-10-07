<?php

return [
    
    //driver kafka
    'driver' => env('MESSAGE_QUEUE_DRIVER', 'kafka'),

    // Kafka configuration (solo per locale)
    'kafka' => [
        'brokers' => env('KAFKA_BROKERS', 'localhost:9092'),
        'topic' => env('KAFKA_TOPIC', 'test-topic'),
        'consumer_group' => env('KAFKA_CONSUMER_GROUP', 'laravel-group'),
        'username' => env('KAFKA_USERNAME', null),
        'password' => env('KAFKA_PASSWORD', null),
    ],

    // Redis configuration (per OVH shared)
    'redis' => [
        'queue' => env('REDIS_QUEUE', 'default'),
    ],
];

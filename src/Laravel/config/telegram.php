<?php

return [
    'default' => 'mybot',

    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),

    'http_client_handler' => null,

    'owner_id' => null,

    'resolve_command_dependencies' => true,

    'base_url' => 'https://api.telegram.org/bot',

    'commands' => [
//        Telegram\Bot\Commands\TestCommand::class,
    ],

    'command_groups' => [],

    'shared_commands' => [],

    'bots' => [
        'mybot' => [
            'username' => 'TelegramBot',
            'token' => env('TELEGRAM_BOT_TOKEN'),
            'certificate_path' => env('TELEGRAM_CERTIFICATE_PATH', 'YOUR-CERTIFICATE-PATH'),
            'webhook_url' => env('TELEGRAM_WEBHOOK_URL', 'YOUR-BOT-WEBHOOK-URL'),
            'commands' => [
                //Acme\Project\Commands\MyTelegramBot\BotCommand::class
            ],
        ],
    ],
];

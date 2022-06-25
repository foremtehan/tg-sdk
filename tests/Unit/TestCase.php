<?php

namespace Telegram\Bot\Tests\Unit;

use Orchestra\Testbench\TestCase as Orchestra;
use Telegram\Bot\Laravel\TelegramServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TelegramServiceProvider::class
        ];
    }
}
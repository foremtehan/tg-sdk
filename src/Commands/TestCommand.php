<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Objects\Update;

class TestCommand extends Command
{
    public function canBeHandled(Update $update): bool
    {
        return true;
    }

    public function handle()
    {
        return $this->say('aa');
    }
}
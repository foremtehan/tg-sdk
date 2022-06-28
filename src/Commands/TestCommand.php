<?php

namespace Telegram\Bot\Commands;

class TestCommand extends Command
{
    public function handle()
    {
        return $this->say('aa');
    }
}
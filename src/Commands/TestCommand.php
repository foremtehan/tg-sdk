<?php

namespace Telegram\Bot\Commands;

class TestCommand extends Command
{
    public function handle()
    {
        $this->say('a');
    }
}
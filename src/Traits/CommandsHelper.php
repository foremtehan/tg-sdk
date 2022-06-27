<?php

namespace Telegram\Bot\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait CommandsHelper
{
    protected function chatId()
    {
        return $this->message->chat?->id;
    }

    protected function forwardedChannelPostMessageId()
    {
        return $this->message->replyToMessage?->forward_from_message_id;
    }

    protected function userId()
    {
        return $this->message->from?->id;
    }

    protected function textIs(string $input)
    {
        return $this->text() == $input;
    }

    public function text(): ?string
    {
        return $this->message->text;
    }

    public function dot(): Collection
    {
        return new Collection(Arr::dot($this));
    }

    public function textStartWith(string $input)
    {
        return Str::startsWith($this->text(), $input);
    }

    protected function textMatch(string $pattern, string $flag = '')
    {
        return Str::match("~$pattern~$flag", $this->text()) ?: null;
    }
}
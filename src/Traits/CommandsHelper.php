<?php

namespace Telegram\Bot\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

trait CommandsHelper
{
    public function chat()
    {
        return $this->message?->chat;
    }

    public function chatId()
    {
        return $this->chat()?->id;
    }

    public function isPrivate()
    {
        return $this->chat()?->type == 'private';
    }

    public function isChannel()
    {
        return $this->has('channel_post');
    }

    public function isGroup()
    {
        return $this->chat()?->type == 'supergroup';
    }

    public function isOwner(): bool
    {
        if (! $id = config('telegram.owner_id')) {
            throw new \LogicException('Owner Id Not Set in Config.');
        }

        return $id == $this->userId();
    }

    public function forwardedChannelPostMessageId()
    {
        return $this->message->replyToMessage?->forward_from_message_id;
    }

    public function userId()
    {
        return $this->message->from?->id;
    }

    public function textIs(string $input)
    {
        return $this->text() == $input;
    }

    public function text(): ?string
    {
        return $this->message?->text;
    }

    public function dot(): Collection
    {
        return new Collection(Arr::dot($this));
    }

    public function textStartWith(string $input)
    {
        return Str::startsWith($this->text(), $input);
    }

    public function textMatch(string $pattern, string $flag = '')
    {
        return Str::match("~$pattern~$flag", $this->text()) ?: null;
    }
}
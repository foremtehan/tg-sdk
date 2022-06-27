<?php

namespace Telegram\Bot\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Telegram\Bot\Objects\Message;
use Illuminate\Support\Collection;

trait CommandsHelper
{
    protected function say($text, array $params = []): Message
    {
        $params = array_merge(['chat_id' => $this->userId(), 'text' => $text], $params);

        return $this->tg->sendMessage($params);
    }

    protected function sayToOwner(string $message)
    {
        return $this->say($message, ['chat_id' => $this->ownerId]);
    }

    public function isOwner()
    {
        return $this->userId() == $this->ownerId;
    }

    public function isGroupAnonymousBot()
    {
        return $this->userId() == 1087968824;
    }

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

    protected function isChannelPost()
    {
        return $this->update->channelPost?->chat?->id == $this->channelId;
    }

    protected function isGroupPost()
    {
        return $this->message?->chat?->id == $this->groupId;
    }

    protected function senderIsChannel()
    {
        return $this->message?->senderChat?->id == $this->channelId;
    }

    public function text(): ?string
    {
        return $this->message->text;
    }

    public function dot(): Collection
    {
        return new Collection(Arr::dot($this->update));
    }

    public function textStartWith(string $input)
    {
        return Str::startsWith($this->text(), $input);
    }

    protected function textMatch(string $pattern, string $flag = '')
    {
        return Str::match("~$pattern~$flag", $this->text()) ?: null;
    }

    protected function getClassNameAsCommand(): string
    {
        return Str::of($this::class)->afterLast('\\')->lower()->prepend('/');
    }
}
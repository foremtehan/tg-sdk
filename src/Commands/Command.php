<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Api;
use Illuminate\Support\Str;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;

abstract class Command
{
    protected Message $message;

    public function __construct(
        protected Api $tg,
        protected Update $update,
        protected ?int $ownerId = null,
        protected ?int $channelId = null,
        protected ?int $groupId = null,
    )
    {
        $this->message = $update->message ?? new Message([]);
    }

    /**
     * Handle incoming update if related
     *
     * @return mixed
     */
    abstract public function handle();

    /**
     * Determine if the incoming update can be handled by the current handler
     *
     * @return bool
     */
    public function canBeHandled()
    {
        return $this->getClassNameAsCommand() == strtolower($this->update->text());
    }

    public function isOwner()
    {
        return $this->update->userId() == $this->ownerId;
    }

    public function isGroupAnonymousBot()
    {
        return $this->update->userId() == 1087968824;
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

    protected function getClassNameAsCommand(): string
    {
        return Str::of($this::class)->afterLast('\\')->lower()->prepend('/')->replace('command', '');
    }

    protected function say($text, array $params = []): Message
    {
        $params = array_merge(['chat_id' => $this->message->from->id, 'text' => $text], $params);

        return $this->tg->sendMessage($params);
    }

}
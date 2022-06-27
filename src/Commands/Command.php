<?php

namespace Telegram\Bot\Commands;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Traits\CommandsHelper;

abstract class Command
{
    use CommandsHelper;

    protected Update $update;

    protected Message $message;

    public function __construct(
        protected Api $tg,
        protected ?int $ownerId = null,
        protected ?int $channelId = null,
        protected ?int $groupId = null,
    )
    {
        $this->update = $tg->getWebhookUpdate();
        $this->message = $this->update->message ?? new Message([]);
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
        return $this->getClassNameAsCommand() == strtolower($this->text());
    }
}
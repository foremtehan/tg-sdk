<?php

namespace Telegram\Bot\Traits;

use Telegram\Bot\Objects\Update;
use Telegram\Bot\Commands\CommandBus;

/**
 * CommandsHandler.
 */
trait CommandsHandler
{
    /**
     * Return Command Bus.
     *
     * @return CommandBus
     */
    protected function getCommandBus(): CommandBus
    {
        return CommandBus::Instance()->setTelegram($this);
    }

    public static function handleUpdateOnly(callable $callable)
    {
        CommandBus::$allowOnly = $callable;
    }

    public static function ignoreUpdateWhen(callable $callable)
    {
        CommandBus::$ignoreWhen = $callable;
    }

    public static function allowedPrivateChats(array $ids)
    {
        CommandBus::$privateIds = $ids;
    }

    public static function allowedChannels(array $ids)
    {
        CommandBus::$channelIds = $ids;
    }

    public static function allowedGroups(array $ids)
    {
        CommandBus::$groupIds = $ids;
    }

    /**
     * Get all registered commands.
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->getCommandBus()->getCommands();
    }

    /**
     * Processes Inbound Commands.
     *
     * @param bool $webhook
     * @param array $params
     * @return Update|Update[]
     */
    public function commandsHandler(bool $webhook = false, array $params = [])
    {
        return $webhook ? $this->useWebHook() : $this->useGetUpdates($params);
    }

    /**
     * Process the update object for a command from your webhook.
     *
     * @return Update
     */
    protected function useWebHook(): Update
    {
        $update = $this->getWebhookUpdate();
        $this->processCommand($update);

        return $update;
    }

    /**
     * Process the update object for a command using the getUpdates method.
     *
     * @return Update[]
     */
    protected function useGetUpdates(array $params = []): array
    {
        $updates = $this->getUpdates($params);

        $highestId = -1;

        foreach ($updates as $update) {
            $highestId = $update->updateId;
            $this->processCommand($update);
        }

        //An update is considered confirmed as soon as getUpdates is called with an offset higher than it's update_id.
        if ($highestId != -1) {
            $this->markUpdateAsRead($highestId);
        }

        return $updates;
    }

    /**
     * Mark updates as read.
     *
     * @param $highestId
     *
     * @return Update[]
     */
    protected function markUpdateAsRead($highestId): array
    {
        $params = [];
        $params['offset'] = $highestId + 1;
        $params['limit'] = 1;

        return $this->getUpdates($params, false);
    }

    /**
     * Check update object for a command and process.
     *
     * @param Update $update
     */
    public function processCommand(Update $update)
    {
        if (value(CommandBus::$ignoreWhen, $update) || value(CommandBus::$allowOnly, $update) === false) {
            return;
        }

        if ($update->isPrivate() && CommandBus::$privateIds && ! in_array($update->userId(), CommandBus::$privateIds)) {
            return;
        }

        if ($update->isChannel() && CommandBus::$channelIds && ! in_array($update->channelId(), CommandBus::$channelIds)) {
            return;
        }

        if ($update->isGroup() && CommandBus::$groupIds && ! in_array($update->chatId(), CommandBus::$groupIds)) {
            return;
        }

        $this->getCommandBus()->handler($update);
    }

    /**
     * Helper to Trigger Commands.
     *
     * @param string $name Command Name
     * @param Update $update Update Object
     * @param null $entity
     *
     * @return mixed
     */
    public function triggerCommand(string $name, Update $update, $entity = null)
    {
        $entity = $entity ?? ['offset' => 0, 'length' => strlen($name) + 1, 'type' => "bot_command"];

        return $this->getCommandBus()->execute(
            $name,
            $update,
            $entity
        );
    }
}

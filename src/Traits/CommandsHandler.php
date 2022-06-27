<?php

namespace Telegram\Bot\Traits;

use Exception;
use ReflectionClass;
use Telegram\Bot\Commands\Command;
use Symfony\Component\Finder\Finder;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Commands\CommandBus;

/**
 * CommandsHandler.
 */
trait CommandsHandler
{
    public static $middleware;

    /**
     * Return Command Bus.
     *
     * @return CommandBus
     */
    protected function getCommandBus(): CommandBus
    {
        return CommandBus::Instance()->setTelegram($this);
    }

    public static function ignoreUpdateWhen(callable $callable)
    {
        self::$middleware = $callable;
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
     * @param null $commandsPath
     * @return Update|Update[]
     */
    public function commandsHandler(bool $webhook = false, $commandsPath = null)
    {
        $commandsPath ??= config('telegram.commands') ?? base_path(DIRECTORY_SEPARATOR.'App'.DIRECTORY_SEPARATOR.'TelegramHandlers');

        if (! $commandsPath) {
            throw new Exception("Commands Not Found");
        }

        return $webhook ? $this->useWebHook($commandsPath) : $this->useGetUpdates($commandsPath);
    }

    /**
     * Process the update object for a command from your webhook.
     *
     * @param $commands
     * @return Update
     */
    protected function useWebHook($commands): Update
    {
        $update = $this->getWebhookUpdate(true);
        $this->processCommand($update, $commands);

        return $update;
    }

    /**
     * Process the update object for a command using the getUpdates method.
     *
     * @return Update[]
     */
    protected function useGetUpdates($commands): array
    {
        $updates = $this->getUpdates();
        $highestId = -1;

        foreach ($updates as $update) {
            $highestId = $update->updateId;
            $this->processCommand($update, $commands);
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
     * @param $commands
     * @return mixed|void
     */
    public function processCommand(Update $update, $commands)
    {
        if (self::$middleware && (self::$middleware)($update)) {
            return;
        }

        $commands = is_string($commands) ? $this->getCommandsFqnFromPath($commands) : $commands;

        foreach ($commands as $command) {
            /** @var Command $command */
            $command = new $command($this, $update);

            if ($command->canBeHandled()) {
                return $command->handle();
            }
        }
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

    private function getCommandsFqnFromPath(string $path)
    {
        $files = Finder::create()->in($path)->files();

        return collect($files)
            ->map(fn($fileClass) => new ReflectionClass($fileClass))
            ->reject->isAbstract()
            ->map(fn(ReflectionClass $r) => $r->getName());
    }
}

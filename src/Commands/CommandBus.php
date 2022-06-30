<?php

namespace Telegram\Bot\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Telegram\Bot\Answers\AnswerBus;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Traits\Singleton;

/**
 * Class CommandBus.
 */
class CommandBus extends AnswerBus
{
    use Singleton;

    /**
     * @var Command[] Holds all commands.
     */
    protected $commands = [];

    /**
     * @var Command[] Holds all commands' aliases.
     */
    protected $commandAliases = [];

    public static $allowOnly;

    public static $ignoreWhen;

    public static $adminOnlyInPrivateChat;

    /**
     * Instantiate Command Bus.
     *
     * @param Api|null $telegram
     */
    public function __construct(Api $telegram = null)
    {
        $this->telegram = $telegram;
    }

    /**
     * Returns the list of commands.
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Add a list of commands.
     *
     * @param array $commands
     *
     * @return CommandBus
     * @throws TelegramSDKException
     */
    public function addCommands(array $commands): self
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }

        return $this;
    }

    /**
     * Add a command to the commands list.
     *
     * @param CommandInterface|string $command Either an object or full path to the command class.
     *
     * @return CommandBus
     * @throws TelegramSDKException
     *
     */
    public function addCommand($command): self
    {
        $this->commands[] = $this->resolveCommand($command);

//        $aliases = $command->getAliases();
//
//        if (empty($aliases)) {
//            return $this;
//        }
//
//        foreach ($command->getAliases() as $alias) {
//            $this->checkForConflicts($command, $alias);
//
//            $this->commandAliases[$alias] = $command;
//        }

        return $this;
    }

    /**
     * Remove a command from the list.
     *
     * @param $name
     *
     * @return CommandBus
     */
    public function removeCommand($name): self
    {
        unset($this->commands[$name]);

        return $this;
    }

    /**
     * Removes a list of commands.
     *
     * @param array $names
     *
     * @return CommandBus
     */
    public function removeCommands(array $names): self
    {
        foreach ($names as $name) {
            $this->removeCommand($name);
        }

        return $this;
    }

    /**
     * Parse a Command for a Match.
     *
     * @param $text
     * @param $offset
     * @param $length
     *
     * @return string
     */
    public function parseCommand($text, $offset, $length): string
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('Message is empty, Cannot parse for command');
        }

        $command = substr(
            $text,
            $offset + 1,
            $length - 1
        );

        // When in group - Ex: /command@MyBot
        if (Str::contains($command, '@') && Str::endsWith($command, ['bot', 'Bot'])) {
            $command = explode('@', $command);
            $command = $command[0];
        }

        return $command;
    }

    /**
     * Handles Inbound Messages and Executes Appropriate Command.
     *
     * @param Update $update
     * @return mixed|void
     */
    protected function handler(Update $update)
    {
        foreach ($this->commands as $command) {
            if ($command->canBeHandled($update)) {
                return $command->make($this->telegram, $update, $update->message->get('entities') ?? []);
            }
        }
    }

    /**
     * Returns all bot_commands detected in the update.
     *
     * @param $message
     *
     * @return Collection
     */
    protected function parseCommandsIn(Collection $message): Collection
    {
        return collect($message->get('entities'))
            ->filter(function ($entity) {
                return $entity['type'] === 'bot_command';
            });
    }

    /**
     * Execute a bot command from the update text.
     *
     * @param array $entity
     * @param Update $update
     */
    protected function process($entity, Update $update)
    {
        $command = $this->parseCommand(
            $update->getMessage()->text,
            $entity['offset'],
            $entity['length']
        );

        $this->execute($command, $update, $entity);
    }

    /**
     * Execute the command.
     *
     * @param string $name
     * @param Update $update
     * @param array $entity
     *
     * @return mixed
     */
    protected function execute(string $name, Update $update, array $entity)
    {
        $command = $this->commands[$name] ??
            $this->commandAliases[$name] ??
            $this->commands['help'] ??
            collect($this->commands)->filter(function ($command) use ($name) {
                return $command instanceof $name;
            })->first() ?? null;

        return $command ? $command->make($this->telegram, $update, $entity) : false;
    }

    /**
     * @param $command
     *
     * @return object
     * @throws TelegramSDKException
     */
    private function resolveCommand($command)
    {
        return $this->makeCommandObj($command);
    }

    /**
     * @param $command
     * @param $alias
     *
     * @throws TelegramSDKException
     */
    private function checkForConflicts($command, $alias)
    {
        if (isset($this->commands[$alias])) {
            throw new TelegramSDKException(
                sprintf(
                    '[Error] Alias [%s] conflicts with command name of "%s" try with another name or remove this alias from the list.',
                    $alias, get_class($command)
                )
            );
        }

        if (isset($this->commandAliases[$alias])) {
            throw new TelegramSDKException(
                sprintf(
                    '[Error] Alias [%s] conflicts with another command\'s alias list: "%s", try with another name or remove this alias from the list.',
                    $alias,
                    get_class($command)
                )
            );
        }
    }

    /**
     * @param $command
     *
     * @return object
     */
    private function makeCommandObj($command)
    {
        if (is_object($command)) {
            return $command;
        }

        if ($this->telegram->hasContainer()) {
            return $this->buildDependencyInjectedAnswer($command);
        }

        return new $command();
    }
}

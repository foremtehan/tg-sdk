<?php

namespace Telegram\Bot\Exceptions;

use Exception;
use Throwable;

/**
 * Class TelegramSDKException.
 */
class TelegramSDKException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, public $params = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Thrown when token is not provided.
     *
     * @param $tokenEnvName
     *
     * @return TelegramSDKException
     */
    public static function tokenNotProvided($tokenEnvName): self
    {
        return new static('Required "token" not supplied in config and could not find fallback environment variable '.$tokenEnvName);
    }
}

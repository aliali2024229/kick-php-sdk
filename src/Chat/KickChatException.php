<?php

namespace Danielhe4rt\KickSDK\Chat;

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use InvalidArgumentException;
use Throwable;

class KickChatException extends InvalidArgumentException
{
    /**
     * Create an exception for when a message fails to send
     * 
     * @param Throwable $exception
     * @return self
     */
    public static function messageSendFailed(Throwable $exception): self
    {
        $message = sprintf("[Kick Chat Send Failed] Context: %s", $exception->getMessage());
        return new self(message: $message, code: $exception->getCode());
    }

    /**
     * Create an exception for when a channel is not found
     * 
     * @param string|int $channelId
     * @return self
     */
    public static function channelNotFound(string|int $channelId): self
    {
        $message = sprintf("[Kick Channel Not Found] Channel with ID '%s' was not found.", $channelId);
        return new self(message: $message);
    }

    /**
     * Create an exception for when a required scope is missing
     * 
     * @param KickOAuthScopesEnum $enum
     * @return self
     */
    public static function missingScope(KickOAuthScopesEnum $enum): self
    {
        $message = sprintf("[Kick Unauthorized] Access denied. You may be missing the required scope (%s).", $enum->value);
        return new self(message: $message, code: 401);
    }

    /**
     * Create an exception for when access is forbidden
     * 
     * @param string $reason
     * @return self
     */
    public static function forbidden(string $reason): self
    {
        $message = sprintf("[Kick Forbidden] %s", $reason);
        return new self(message: $message, code: 403);
    }
} 
<?php

namespace Danielhe4rt\KickSDK\Streams;

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use InvalidArgumentException;
use Throwable;

class KickStreamException extends InvalidArgumentException
{
    public static function channelFetchFailed(Throwable $exception): self
    {
        $message = sprintf("[Kick Channel Fetch Failed] Context: %s", $exception->getMessage());
        return new self(message: $message, code: $exception->getCode());
    }

    public static function channelNotFound(string $channelId): self
    {
        $message = sprintf("[Kick Channel Not Found] Channel with ID '%s' was not found.", $channelId);
        return new self(message: $message);
    }

    public static function missingScope(KickOAuthScopesEnum $enum): self
    {
        $message = sprintf("[Kick Unauthorized] Access denied. You may be missing the required scope (%s).", $enum->value);
        return new self(message: $message, code: 401);
    }
} 
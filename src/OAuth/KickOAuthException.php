<?php

namespace Danielhe4rt\KickSDK\OAuth;

use InvalidArgumentException;

class KickOAuthException extends InvalidArgumentException
{
    public static function authenticationFailed(string $context, int $status): self
    {
        $message = sprintf("[Kick  Authentication failed] Context: %s", $context);
        return new self(message: $message, code: $status);
    }

    public static function refreshTokenFailed(string $context, int $status): self
    {
        $message = sprintf("[Kick Refresh Token failed] Context: %s", $context);
        return new self(message: $message, code: $status);
    }

    public static function revokeTokenFailed(string $context, int $status): self
    {
        $message = sprintf("[Kick Revoke Token failed] Context: %s", $context);
        return new self(message: $message, code: $status);
    }

    public static function invalidScope(string $invalidScope): self
    {
        $message = sprintf("[Kick Invalid scope] The provided scope '%s' is invalid. Check the 'KickAuthScopesEnum' for the given options.", $invalidScope);
        return new self(message: $message);
    }
}
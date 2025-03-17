<?php

namespace Danielhe4rt\KickSDK\Users;

use InvalidArgumentException;

class UserException extends InvalidArgumentException
{
    public static function userFetchFailed(string $context, int $status): self
    {
        $message = sprintf("[Kick User Fetch Failed] Context: %s", $context);
        return new self(message: $message, code: $status);
    }

    public static function userNotFound(string $userId): self
    {
        $message = sprintf("[Kick User Not Found] User with ID '%s' was not found.", $userId);
        return new self(message: $message);
    }

    public static function usersNotFound(): self
    {
        return new self(message: "[Kick Users Not Found] No users were found. Are you sure you're passing the right id's?");
    }
} 
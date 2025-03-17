<?php

namespace Danielhe4rt\KickSDK\PublicKey;

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use InvalidArgumentException;
use Throwable;

class KickPublicKeyException extends InvalidArgumentException
{

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
     * Create an exception for when public key retrieval fails
     * 
     * @param Throwable $exception
     * @return self
     */
    public static function retrievalFailed(Throwable $exception): self
    {
        $message = sprintf("[Kick Public Key Retrieval Failed] Context: %s", $exception->getMessage());
        return new self(message: $message, code: $exception->getCode());
    }
} 
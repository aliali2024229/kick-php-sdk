<?php

namespace DanielHe4rt\KickSDK\OAuth\DTOs;

readonly class RefreshTokenDTO
{
    public function __construct(
        public string $refreshToken,
    ) {}

    public static function make(string $refreshToken): self
    {
        return new self(
            refreshToken: $refreshToken
        );
    }
}

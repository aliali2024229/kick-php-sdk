<?php

namespace DanielHe4rt\KickSDK\OAuth\DTOs;

readonly class AuthenticateDTO
{
    public function __construct(
        public string $code,
        public string $codeVerifier,
        public ?string $redirectUri = null,
    ) {}

    public static function make(string $code, string $codeVerifier, string $redirectUrl): self
    {
        return new self(
            code: $code,
            codeVerifier: $codeVerifier,
            redirectUri: $redirectUrl,
        );
    }
}

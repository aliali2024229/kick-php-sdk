<?php

namespace Danielhe4rt\KickSDK\OAuth\Entities;

readonly class CodeVerifierEntity
{
    public function __construct(
        private string $code,
        private string $codeVerifier
    )
    {

    }

    public static function make(): self
    {
        $codeVerifier = self::generateCodeVerifier();
        $codeChallenge = self::generateCodeChallenge($codeVerifier);

        return new self(
            code: $codeChallenge,
            codeVerifier: $codeVerifier
        );
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getVerifier(): string
    {
        return $this->codeVerifier;
    }

    private static function generateCodeChallenge(string $codeVerifier): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    }

    private static function generateCodeVerifier(int $length = 128): string
    {

        return bin2hex(random_bytes($length / 2));
    }
}
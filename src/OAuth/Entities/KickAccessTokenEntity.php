<?php

namespace DanielHe4rt\KickSDK\OAuth\Entities;

class KickAccessTokenEntity
{
    public function __construct(
        public string $accessToken,
        public int $expires_in,
        public string $refreshToken,
        public string $scope,
        public string $token_type,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['access_token'],
            $data['expires_in'],
            $data['refresh_token'],
            $data['scope'],
            $data['token_type']
        );
    }
}

<?php

namespace Danielhe4rt\KickSDK\OAuth\Entities;

class KickAccessTokenEntity
{

    public function __construct(
        public string $access_token,
        public int    $expires_in,
        public string $refresh_token,
        public string $scope,
        public string $token_type,
    )
    {
    }

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
<?php

namespace Danielhe4rt\KickSDK\OAuth\Entities;

class KickIntrospectTokenEntity
{
    public function __construct(
        public bool   $active,
        public string $clientId,
        public int    $exp,
        public string $scope,
        public string $tokenType,
        public string $message
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            active: $data['data']['active'],
            clientId: $data['data']['client_id'],
            exp: $data['data']['exp'],
            scope: $data['data']['scope'],
            tokenType: $data['data']['token_type'],
            message: $data['message']
        );
    }
}
<?php

namespace Danielhe4rt\KickSDK\OAuth\Entities;


class AuthenticatedUserEntity
{
    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $profile_picture_url,
    )
    {

    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (string)($data['user_id'] ?? ''),
            username: (string)($data['name'] ?? ''),
            email: (string)($data['email'] ?? ''),
            profile_picture_url: (string)($data['profile_picture'] ?? '')
        );
    }
}
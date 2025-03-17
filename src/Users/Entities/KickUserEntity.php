<?php

namespace DanielHe4rt\KickSDK\Users\Entities;

use JsonSerializable;

readonly class KickUserEntity implements JsonSerializable
{
    public function __construct(
        public int $userId,
        public string $username,
        public string $profile_picture,
        public ?string $email = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            username: $data['name'],
            profile_picture: $data['profile_picture'],
            email: $data['email'] ?? null,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'user_id' => $this->userId,
            'name' => $this->username,
            'profile_picture' => $this->profile_picture,
            'email' => $this->email,
        ];
    }
}

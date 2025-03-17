<?php

namespace DanielHe4rt\KickSDK\PublicKey\Entities;

use JsonSerializable;

readonly class KickPublicKeyEntity implements JsonSerializable
{
    /**
     * @param  string  $publicKey  The public key used for verifying signatures
     */
    public function __construct(
        public string $publicKey,
    ) {}

    /**
     * Create a new KickPublicKeyEntity from an array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            publicKey: $data['public_key'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'public_key' => $this->publicKey,
        ];
    }
}

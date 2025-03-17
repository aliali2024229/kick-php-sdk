<?php

namespace DanielHe4rt\KickSDK\Streams\Entities;

use JsonSerializable;

readonly class KickCategoryEntity implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $thumbnail,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            thumbnail: $data['thumbnail'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
        ];
    }
}

<?php

namespace DanielHe4rt\KickSDK\Streams\DTOs;

use JsonSerializable;

readonly class UpdateChannelDTO implements JsonSerializable
{
    public function __construct(
        public ?int $categoryId = null,
        public ?string $streamTitle = null,
    ) {}

    public function jsonSerialize(): array
    {
        $result = [];

        if ($this->categoryId) {
            $result['category_id'] = $this->categoryId;
        }

        if ($this->streamTitle) {
            $result['stream_title'] = $this->streamTitle;
        }

        return $result;
    }
}

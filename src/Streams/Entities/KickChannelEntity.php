<?php

namespace Danielhe4rt\KickSDK\Streams\Entities;

use JsonSerializable;

readonly class KickChannelEntity implements JsonSerializable
{
    public function __construct(
        public string $banner_picture,
        public int $broadcaster_user_id,
        public KickCategoryEntity $category,
        public string $channel_description,
        public string $slug,
        public ?KickStreamEntity $stream,
        public string $stream_title,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            banner_picture: $data['banner_picture'],
            broadcaster_user_id: $data['broadcaster_user_id'],
            category: KickCategoryEntity::fromArray($data['category']),
            channel_description: $data['channel_description'],
            slug: $data['slug'],
            stream: isset($data['stream']) ? KickStreamEntity::fromArray($data['stream']) : null,
            stream_title: $data['stream_title'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'banner_picture' => $this->banner_picture,
            'broadcaster_user_id' => $this->broadcaster_user_id,
            'category' => $this->category->jsonSerialize(),
            'channel_description' => $this->channel_description,
            'slug' => $this->slug,
            'stream' => $this->stream?->jsonSerialize(),
            'stream_title' => $this->stream_title,
        ];
    }
} 
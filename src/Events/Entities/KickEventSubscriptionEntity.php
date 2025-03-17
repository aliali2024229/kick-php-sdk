<?php

namespace Danielhe4rt\KickSDK\Events\Entities;

use Danielhe4rt\KickSDK\Events\Enums\KickEventTypeEnum;
use JsonSerializable;

readonly class KickEventSubscriptionEntity implements JsonSerializable
{
    /**
     * @param string $id The subscription ID
     * @param string $appId The app ID
     * @param int|null $broadcasterUserId The broadcaster user ID (if applicable)
     * @param KickEventTypeEnum $event The event name
     * @param int $version The event version
     * @param string $method The subscription method (e.g., webhook)
     * @param string $createdAt When the subscription was created
     * @param string $updatedAt When the subscription was last updated
     */
    public function __construct(
        public string $id,
        public string $appId,
        public ?int $broadcasterUserId,
        public KickEventTypeEnum $event,
        public int $version,
        public string $method,
        public string $createdAt,
        public string $updatedAt,
    )
    {
    }

    /**
     * Create a new KickEventSubscriptionEntity from an array
     * 
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            appId: $data['app_id'],
            broadcasterUserId: $data['broadcaster_user_id'] ?? null,
            event: KickEventTypeEnum::from($data['event']),
            version: $data['version'],
            method: $data['method'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->id,
            'app_id' => $this->appId,
            'event' => $this->event->value,
            'version' => $this->version,
            'method' => $this->method,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
        
        if ($this->broadcasterUserId !== null) {
            $data['broadcaster_user_id'] = $this->broadcasterUserId;
        }
        
        return $data;
    }
} 
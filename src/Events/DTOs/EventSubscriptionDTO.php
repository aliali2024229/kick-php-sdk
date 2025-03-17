<?php

namespace Danielhe4rt\KickSDK\Events\DTOs;

use Danielhe4rt\KickSDK\Events\Enums\KickEventTypeEnum;
use InvalidArgumentException;
use JsonSerializable;

readonly class EventSubscriptionDTO implements JsonSerializable
{
    /**
     * @param KickEventTypeEnum $name The event name (e.g., 'chat.message.sent')
     * @param int $version The event version
     */
    public function __construct(
        public KickEventTypeEnum $name,
        public int $version,
    )
    {

        if ($this->version <= 0) {
            throw new InvalidArgumentException('Event version must be greater than 0');
        }
    }

    /**
     * Create a new EventSubscriptionDTO instance
     *
     * @param KickEventTypeEnum $name The event name
     * @param int $version The event version
     * @return self
     */
    public static function make(
        KickEventTypeEnum $name,
        int $version = 1,
    ): self
    {
        return new self(
            name: $name,
            version: $version
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->value,
            'version' => $this->version,
        ];
    }
}
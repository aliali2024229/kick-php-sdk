<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks\Entities;

use JsonSerializable;

readonly class KickWebhookEmoteEntity implements JsonSerializable
{
    /**
     * @param  string  $emoteId  The ID of the emote
     * @param  array  $positions  Array of position objects with 's' (start) and 'e' (end) properties
     */
    public function __construct(
        public string $emoteId,
        public array $positions,
    ) {}

    /**
     * Create a new KickWebhookEmoteEntity from an array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            emoteId: $data['emote_id'],
            positions: $data['positions'] ?? [],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'emote_id' => $this->emoteId,
            'positions' => $this->positions,
        ];
    }
}

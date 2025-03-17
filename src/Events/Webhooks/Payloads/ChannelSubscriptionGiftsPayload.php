<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks\Payloads;

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DateTimeImmutable;

readonly class ChannelSubscriptionGiftsPayload extends KickWebhookPayload
{
    /**
     * @param  KickWebhookEventTypeEnum  $eventType  The type of event
     * @param  int  $eventVersion  The version of the event
     * @param  KickWebhookUserEntity  $broadcaster  The broadcaster information
     * @param  KickWebhookUserEntity  $gifter  The gifter information (may be anonymous)
     * @param  KickWebhookUserEntity[]  $giftees  Array of users who received the gift
     * @param  DateTimeImmutable  $createdAt  When the gifts were sent
     */
    public function __construct(
        KickWebhookEventTypeEnum $eventType,
        int $eventVersion,
        KickWebhookUserEntity $broadcaster,
        public KickWebhookUserEntity $gifter,
        public array $giftees,
        public DateTimeImmutable $createdAt,
    ) {
        parent::__construct($eventType, $eventVersion, $broadcaster);
    }

    /**
     * Create a webhook payload from an array
     *
     * @param  array  $data  The payload data
     * @param  KickWebhookEventTypeEnum  $eventType  The event type
     * @param  int  $eventVersion  The event version
     */
    public static function fromArray(array $data, KickWebhookEventTypeEnum $eventType, int $eventVersion): static
    {
        $broadcaster = KickWebhookUserEntity::fromArray($data['broadcaster']);
        $gifter = KickWebhookUserEntity::fromArray($data['gifter']);

        $giftees = [];
        foreach ($data['giftees'] ?? [] as $gifteeData) {
            $giftees[] = KickWebhookUserEntity::fromArray($gifteeData);
        }

        $createdAt = new DateTimeImmutable($data['created_at']);

        return new self(
            eventType: $eventType,
            eventVersion: $eventVersion,
            broadcaster: $broadcaster,
            gifter: $gifter,
            giftees: $giftees,
            createdAt: $createdAt,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'broadcaster' => $this->broadcaster->jsonSerialize(),
            'gifter' => $this->gifter->jsonSerialize(),
            'giftees' => $this->giftees,
            'created_at' => $this->createdAt->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}

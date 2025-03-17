<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks\Payloads;

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DateTimeImmutable;

readonly class LivestreamStatusUpdatedPayload extends KickWebhookPayload
{
    /**
     * @param  KickWebhookEventTypeEnum  $eventType  The type of event
     * @param  int  $eventVersion  The version of the event
     * @param  KickWebhookUserEntity  $broadcaster  The broadcaster information
     * @param  bool  $isLive  Whether the stream is live
     * @param  string  $title  The stream title
     * @param  DateTimeImmutable  $startedAt  When the stream started
     * @param  DateTimeImmutable|null  $endedAt  When the stream ended (null if still live)
     */
    public function __construct(
        KickWebhookEventTypeEnum $eventType,
        int $eventVersion,
        KickWebhookUserEntity $broadcaster,
        public bool $isLive,
        public string $title,
        public DateTimeImmutable $startedAt,
        public ?DateTimeImmutable $endedAt,
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
        $startedAt = new DateTimeImmutable($data['started_at']);
        $endedAt = isset($data['ended_at']) && $data['ended_at'] !== null
            ? new DateTimeImmutable($data['ended_at'])
            : null;

        return new self(
            eventType: $eventType,
            eventVersion: $eventVersion,
            broadcaster: $broadcaster,
            isLive: $data['is_live'],
            title: $data['title'],
            startedAt: $startedAt,
            endedAt: $endedAt,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'broadcaster' => $this->broadcaster->jsonSerialize(),
            'is_live' => $this->isLive,
            'title' => $this->title,
            'started_at' => $this->startedAt->format('Y-m-d\TH:i:s\Z'),
            'ended_at' => $this->endedAt?->format('Y-m-d\TH:i:s\Z'),
        ];
    }
}

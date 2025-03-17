<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks\Payloads;

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\KickWebhookException;
use JsonSerializable;

abstract readonly class KickWebhookPayload implements JsonSerializable
{
    /**
     * @param  KickWebhookEventTypeEnum  $eventType  The type of event
     * @param  int  $eventVersion  The version of the event
     * @param  KickWebhookUserEntity  $broadcaster  The broadcaster information
     */
    public function __construct(
        public KickWebhookEventTypeEnum $eventType,
        public int $eventVersion,
        public KickWebhookUserEntity $broadcaster,
    ) {}

    /**
     * Create a webhook payload from the request data and headers
     *
     * @param  array  $data  The request body data
     * @param  array  $headers  The request headers
     */
    public static function fromRequest(array $headers, array $data): ?static
    {
        $eventType = KickWebhookEventTypeEnum::fromHeader($headers['Kick-Event-Type'] ?? null);
        if ($eventType === null) {
            throw KickWebhookException::invalidEventType($eventType);
        }

        $eventVersion = (int) ($headers['Kick-Event-Version'] ?? 0);

        if ($eventVersion <= 0) {
            throw KickWebhookException::invalidEventVersion($eventVersion);
        }

        return static::fromArray($data, $eventType, $eventVersion);
    }

    /**
     * Create a webhook payload from an array
     *
     * @param  array  $data  The payload data
     * @param  KickWebhookEventTypeEnum  $eventType  The event type
     * @param  int  $eventVersion  The event version
     */
    abstract public static function fromArray(array $data, KickWebhookEventTypeEnum $eventType, int $eventVersion): static;
}

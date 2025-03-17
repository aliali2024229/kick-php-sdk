<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks\Payloads;

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookEmoteEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;

readonly class ChatMessageSentPayload extends KickWebhookPayload
{
    /**
     * @param  KickWebhookEventTypeEnum  $eventType  The type of event
     * @param  int  $eventVersion  The version of the event
     * @param  KickWebhookUserEntity  $broadcaster  The broadcaster information
     * @param  string  $messageId  The unique ID of the message
     * @param  KickWebhookUserEntity  $sender  The sender information
     * @param  string  $content  The message content
     * @param  KickWebhookEmoteEntity[]  $emotes  Array of emotes used in the message
     */
    public function __construct(
        KickWebhookEventTypeEnum $eventType,
        int $eventVersion,
        KickWebhookUserEntity $broadcaster,
        public string $messageId,
        public KickWebhookUserEntity $sender,
        public string $content,
        public array $emotes,
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
        $sender = KickWebhookUserEntity::fromArray($data['sender']);

        $emotes = [];
        foreach ($data['emotes'] ?? [] as $emoteData) {
            $emotes[] = KickWebhookEmoteEntity::fromArray($emoteData);
        }

        return new self(
            eventType: $eventType,
            eventVersion: $eventVersion,
            broadcaster: $broadcaster,
            messageId: $data['message_id'],
            sender: $sender,
            content: $data['content'],
            emotes: $emotes,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'message_id' => $this->messageId,
            'broadcaster' => $this->broadcaster->jsonSerialize(),
            'sender' => $this->sender->jsonSerialize(),
            'content' => $this->content,
            'emotes' => $this->emotes,
        ];
    }
}

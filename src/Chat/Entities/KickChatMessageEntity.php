<?php

namespace Danielhe4rt\KickSDK\Chat\Entities;

use JsonSerializable;

readonly class KickChatMessageEntity implements JsonSerializable
{
    /**
     * @param bool $isSent Whether the message was sent successfully
     * @param string $messageId The ID of the message that was sent
     */
    public function __construct(
        public bool   $isSent,
        public string $messageId,
    )
    {
    }

    /**
     * Create a new KickChatMessageEntity from an array
     * 
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isSent: $data['is_sent'],
            messageId: $data['message_id'],
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'is_sent' => $this->isSent,
            'message_id' => $this->messageId,
        ];
    }
} 
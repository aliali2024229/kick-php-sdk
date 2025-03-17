<?php

namespace Danielhe4rt\KickSDK\Events\Entities;

use JsonSerializable;

readonly class KickEventSubscriptionResponseEntity implements JsonSerializable
{
    /**
     * @param  string|null  $error  Error message if subscription failed
     * @param  string  $name  Event name
     * @param  string|null  $subscriptionId  Subscription ID if successful
     * @param  int  $version  Event version
     */
    public function __construct(
        public ?string $error,
        public string $name,
        public ?string $subscriptionId,
        public int $version,
    ) {}

    /**
     * Create a new KickEventSubscriptionResponseEntity from an array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            error: $data['error'] ?? null,
            name: $data['name'],
            subscriptionId: $data['subscription_id'] ?? null,
            version: $data['version'],
        );
    }

    /**
     * Check if the subscription was successful
     */
    public function isSuccessful(): bool
    {
        return $this->error === null && $this->subscriptionId !== null;
    }

    public function jsonSerialize(): array
    {
        return [
            'error' => $this->error,
            'name' => $this->name,
            'subscription_id' => $this->subscriptionId,
            'version' => $this->version,
        ];
    }
}

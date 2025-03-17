<?php

namespace Danielhe4rt\KickSDK\Events\DTOs;

use DanielHe4rt\KickSDK\Events\Enums\KickEventMethodEnum;
use InvalidArgumentException;
use JsonSerializable;

readonly class CreateEventSubscriptionDTO implements JsonSerializable
{
    /**
     * @param  EventSubscriptionDTO[]  $events  Array of events to subscribe to
     * @param  KickEventMethodEnum  $method  The subscription method (currently only webhook is supported)
     */
    public function __construct(
        public array $events,
        public KickEventMethodEnum $method = KickEventMethodEnum::Webhook,
    ) {
        if (empty($this->events)) {
            throw new InvalidArgumentException('At least one event must be specified');
        }

        foreach ($this->events as $event) {
            if (! $event instanceof EventSubscriptionDTO) {
                throw new InvalidArgumentException('Each event must be an instance of EventSubscriptionDTO');
            }
        }
    }

    /**
     * Create a new CreateEventSubscriptionDTO instance
     *
     * @param  EventSubscriptionDTO[]  $events  Array of events to subscribe to
     * @param  KickEventMethodEnum  $method  The subscription method
     */
    public static function make(
        array $events,
        KickEventMethodEnum $method = KickEventMethodEnum::Webhook,
    ): self {
        foreach ($events as $event) {
            if (! $event instanceof EventSubscriptionDTO) {
                throw new InvalidArgumentException('Each event must be an instance of EventSubscriptionDTO');
            }
        }

        return new self(
            events: $events,
            method: $method
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'events' => array_map(fn (EventSubscriptionDTO $event) => $event->jsonSerialize(), $this->events),
            'method' => $this->method->value,
        ];
    }
}

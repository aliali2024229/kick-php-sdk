<?php

use Danielhe4rt\KickSDK\Events\DTOs\CreateEventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\DTOs\EventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\Enums\KickEventMethodEnum;
use Danielhe4rt\KickSDK\Events\Enums\KickEventTypeEnum;

test('can create CreateEventSubscriptionDTO with constructor', function () {
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1),
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChannelFollowed, version: 1)
    ];
    
    $dto = new CreateEventSubscriptionDTO(
        events: $events
    );

    expect($dto->events)->toBe($events)
        ->and($dto->method)->toBe(KickEventMethodEnum::Webhook);
});

test('can create CreateEventSubscriptionDTO with explicit method', function () {
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1)
    ];
    
    $dto = new CreateEventSubscriptionDTO(
        events: $events,
        method: KickEventMethodEnum::Webhook
    );

    expect($dto->events)->toBe($events)
        ->and($dto->method)->toBe(KickEventMethodEnum::Webhook);
});

test('can create CreateEventSubscriptionDTO using make method', function () {
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1)
    ];
    
    $dto = CreateEventSubscriptionDTO::make(
        events: $events
    );

    expect($dto->events)->toBe($events)
        ->and($dto->method)->toBe(KickEventMethodEnum::Webhook);
});

test('can serialize CreateEventSubscriptionDTO to array', function () {
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1)
    ];
    
    $dto = new CreateEventSubscriptionDTO(
        events: $events
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'events' => [
            [
                'name' => 'chat.message.sent',
                'version' => 1
            ]
        ],
        'method' => 'webhook'
    ]);
});

test('throws exception when events array is empty', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('At least one event must be specified');

    new CreateEventSubscriptionDTO(
        events: []
    );
});

test('throws exception when event is not an instance of EventSubscriptionDTO', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Each event must be an instance of EventSubscriptionDTO');

    new CreateEventSubscriptionDTO(
        events: [
            [
                'name' => 'chat.message.sent',
                'version' => 1
            ]
        ]
    );
}); 
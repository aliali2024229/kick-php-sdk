<?php

use Danielhe4rt\KickSDK\Events\DTOs\EventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\Enums\KickEventTypeEnum;

test('can create EventSubscriptionDTO with constructor', function () {
    $dto = new EventSubscriptionDTO(
        name: KickEventTypeEnum::ChatMessageSent,
        version: 1
    );

    expect($dto->name->value)->toBe('chat.message.sent')
        ->and($dto->version)->toBe(1);
});

test('can create EventSubscriptionDTO using make method', function () {
    $dto = EventSubscriptionDTO::make(
        name: KickEventTypeEnum::ChatMessageSent,
        version: 1
    );

    expect($dto->name->value)->toBe('chat.message.sent')
        ->and($dto->version)->toBe(1);
});

test('can create EventSubscriptionDTO with default version', function () {
    $dto = EventSubscriptionDTO::make(
        name: KickEventTypeEnum::ChatMessageSent
    );

    expect($dto->name->value)->toBe('chat.message.sent')
        ->and($dto->version)->toBe(1);
});

test('can serialize EventSubscriptionDTO to array', function () {
    $dto = new EventSubscriptionDTO(
        name: KickEventTypeEnum::ChatMessageSent,
        version: 1
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'name' => 'chat.message.sent',
        'version' => 1
    ]);
});


test('throws exception when version is less than or equal to 0', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Event version must be greater than 0');

    new EventSubscriptionDTO(
        name: KickEventTypeEnum::ChannelFollowed,
        version: 0
    );
}); 
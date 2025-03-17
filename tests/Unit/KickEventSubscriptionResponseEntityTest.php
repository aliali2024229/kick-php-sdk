<?php

use Danielhe4rt\KickSDK\Events\Entities\KickEventSubscriptionResponseEntity;

test('can create KickEventSubscriptionResponseEntity with constructor', function () {
    $entity = new KickEventSubscriptionResponseEntity(
        error: null,
        name: 'chat.message.sent',
        subscriptionId: 'sub_123',
        version: 1
    );

    expect($entity->error)->toBeNull()
        ->and($entity->name)->toBe('chat.message.sent')
        ->and($entity->subscriptionId)->toBe('sub_123')
        ->and($entity->version)->toBe(1);
});

test('can create KickEventSubscriptionResponseEntity from array', function () {
    $data = [
        'error' => null,
        'name' => 'chat.message.sent',
        'subscription_id' => 'sub_123',
        'version' => 1
    ];

    $entity = KickEventSubscriptionResponseEntity::fromArray($data);

    expect($entity->error)->toBeNull()
        ->and($entity->name)->toBe('chat.message.sent')
        ->and($entity->subscriptionId)->toBe('sub_123')
        ->and($entity->version)->toBe(1);
});

test('can create KickEventSubscriptionResponseEntity with error', function () {
    $data = [
        'error' => 'Invalid event name',
        'name' => 'invalid.event',
        'subscription_id' => null,
        'version' => 1
    ];

    $entity = KickEventSubscriptionResponseEntity::fromArray($data);

    expect($entity->error)->toBe('Invalid event name')
        ->and($entity->name)->toBe('invalid.event')
        ->and($entity->subscriptionId)->toBeNull()
        ->and($entity->version)->toBe(1);
});

test('can check if subscription was successful', function () {
    $successEntity = new KickEventSubscriptionResponseEntity(
        error: null,
        name: 'chat.message.sent',
        subscriptionId: 'sub_123',
        version: 1
    );

    $failedEntity = new KickEventSubscriptionResponseEntity(
        error: 'Invalid event name',
        name: 'invalid.event',
        subscriptionId: null,
        version: 1
    );

    expect($successEntity->isSuccessful())->toBeTrue()
        ->and($failedEntity->isSuccessful())->toBeFalse();
});

test('can serialize KickEventSubscriptionResponseEntity to array', function () {
    $entity = new KickEventSubscriptionResponseEntity(
        error: null,
        name: 'chat.message.sent',
        subscriptionId: 'sub_123',
        version: 1
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'error' => null,
        'name' => 'chat.message.sent',
        'subscription_id' => 'sub_123',
        'version' => 1
    ]);
}); 
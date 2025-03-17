<?php

use DanielHe4rt\KickSDK\Events\Entities\KickEventSubscriptionEntity;
use DanielHe4rt\KickSDK\Events\Enums\KickEventTypeEnum;

test('can create KickEventSubscriptionEntity with constructor', function () {
    $entity = new KickEventSubscriptionEntity(
        id: 'sub_123',
        appId: 'app_456',
        broadcasterUserId: 789,
        event: KickEventTypeEnum::ChatMessageSent,
        version: 1,
        method: 'webhook',
        createdAt: '2023-01-01T00:00:00Z',
        updatedAt: '2023-01-01T00:00:00Z'
    );

    expect($entity->id)->toBe('sub_123')
        ->and($entity->appId)->toBe('app_456')
        ->and($entity->broadcasterUserId)->toBe(789)
        ->and($entity->event)->toBe(KickEventTypeEnum::ChatMessageSent)
        ->and($entity->version)->toBe(1)
        ->and($entity->method)->toBe('webhook')
        ->and($entity->createdAt)->toBe('2023-01-01T00:00:00Z')
        ->and($entity->updatedAt)->toBe('2023-01-01T00:00:00Z');
});

test('can create KickEventSubscriptionEntity with null broadcaster user ID', function () {
    $entity = new KickEventSubscriptionEntity(
        id: 'sub_123',
        appId: 'app_456',
        broadcasterUserId: null,
        event: KickEventTypeEnum::ChatMessageSent,
        version: 1,
        method: 'webhook',
        createdAt: '2023-01-01T00:00:00Z',
        updatedAt: '2023-01-01T00:00:00Z'
    );

    expect($entity->id)->toBe('sub_123')
        ->and($entity->appId)->toBe('app_456')
        ->and($entity->broadcasterUserId)->toBeNull()
        ->and($entity->event)->toBe(KickEventTypeEnum::ChatMessageSent)
        ->and($entity->version)->toBe(1)
        ->and($entity->method)->toBe('webhook')
        ->and($entity->createdAt)->toBe('2023-01-01T00:00:00Z')
        ->and($entity->updatedAt)->toBe('2023-01-01T00:00:00Z');
});

test('can create KickEventSubscriptionEntity from array', function () {
    $data = [
        'id' => 'sub_123',
        'app_id' => 'app_456',
        'broadcaster_user_id' => 789,
        'event' => 'chat.message.sent',
        'version' => 1,
        'method' => 'webhook',
        'created_at' => '2023-01-01T00:00:00Z',
        'updated_at' => '2023-01-01T00:00:00Z',
    ];

    $entity = KickEventSubscriptionEntity::fromArray($data);

    expect($entity->id)->toBe('sub_123')
        ->and($entity->appId)->toBe('app_456')
        ->and($entity->broadcasterUserId)->toBe(789)
        ->and($entity->event)->toBe(KickEventTypeEnum::ChatMessageSent)
        ->and($entity->version)->toBe(1)
        ->and($entity->method)->toBe('webhook')
        ->and($entity->createdAt)->toBe('2023-01-01T00:00:00Z')
        ->and($entity->updatedAt)->toBe('2023-01-01T00:00:00Z');
});

test('can create KickEventSubscriptionEntity from array without broadcaster user ID', function () {
    $data = [
        'id' => 'sub_123',
        'app_id' => 'app_456',
        'event' => 'chat.message.sent',
        'version' => 1,
        'method' => 'webhook',
        'created_at' => '2023-01-01T00:00:00Z',
        'updated_at' => '2023-01-01T00:00:00Z',
    ];

    $entity = KickEventSubscriptionEntity::fromArray($data);

    expect($entity->id)->toBe('sub_123')
        ->and($entity->appId)->toBe('app_456')
        ->and($entity->broadcasterUserId)->toBeNull()
        ->and($entity->event)->toBe(KickEventTypeEnum::ChatMessageSent)
        ->and($entity->version)->toBe(1)
        ->and($entity->method)->toBe('webhook')
        ->and($entity->createdAt)->toBe('2023-01-01T00:00:00Z')
        ->and($entity->updatedAt)->toBe('2023-01-01T00:00:00Z');
});

test('can serialize KickEventSubscriptionEntity to array', function () {
    $entity = new KickEventSubscriptionEntity(
        id: 'sub_123',
        appId: 'app_456',
        broadcasterUserId: 789,
        event: KickEventTypeEnum::ChatMessageSent,
        version: 1,
        method: 'webhook',
        createdAt: '2023-01-01T00:00:00Z',
        updatedAt: '2023-01-01T00:00:00Z'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->tobe([
        'id' => 'sub_123',
        'app_id' => 'app_456',
        'event' => 'chat.message.sent',
        'version' => 1,
        'method' => 'webhook',
        'created_at' => '2023-01-01T00:00:00Z',
        'updated_at' => '2023-01-01T00:00:00Z',
        'broadcaster_user_id' => 789,
    ]);
});

test('can serialize KickEventSubscriptionEntity to array without broadcaster user ID', function () {
    $entity = new KickEventSubscriptionEntity(
        id: 'sub_123',
        appId: 'app_456',
        broadcasterUserId: null,
        event: KickEventTypeEnum::ChatMessageSent,
        version: 1,
        method: 'webhook',
        createdAt: '2023-01-01T00:00:00Z',
        updatedAt: '2023-01-01T00:00:00Z'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'id' => 'sub_123',
        'app_id' => 'app_456',
        'event' => 'chat.message.sent',
        'version' => 1,
        'method' => 'webhook',
        'created_at' => '2023-01-01T00:00:00Z',
        'updated_at' => '2023-01-01T00:00:00Z',
    ]);
});

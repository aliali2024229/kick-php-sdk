<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\LivestreamStatusUpdatedPayload;

test('can create LivestreamStatusUpdatedPayload with constructor for stream start', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $startedAt = new DateTimeImmutable('2025-01-01T11:00:00+11:00');

    $payload = new LivestreamStatusUpdatedPayload(
        eventType: KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        eventVersion: 1,
        broadcaster: $broadcaster,
        isLive: true,
        title: 'Stream Title',
        startedAt: $startedAt,
        endedAt: null
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBe($broadcaster)
        ->and($payload->isLive)->toBeTrue()
        ->and($payload->title)->toBe('Stream Title')
        ->and($payload->startedAt)->toBe($startedAt)
        ->and($payload->endedAt)->toBeNull();
});

test('can create LivestreamStatusUpdatedPayload with constructor for stream end', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $startedAt = new DateTimeImmutable('2025-01-01T11:00:00+11:00');
    $endedAt = new DateTimeImmutable('2025-01-01T15:00:00+11:00');

    $payload = new LivestreamStatusUpdatedPayload(
        eventType: KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        eventVersion: 1,
        broadcaster: $broadcaster,
        isLive: false,
        title: 'Stream Title',
        startedAt: $startedAt,
        endedAt: $endedAt
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBe($broadcaster)
        ->and($payload->isLive)->toBeFalse()
        ->and($payload->title)->toBe('Stream Title')
        ->and($payload->startedAt)->toBe($startedAt)
        ->and($payload->endedAt)->toBe($endedAt);
});

test('can create LivestreamStatusUpdatedPayload from array for stream start', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'is_live' => true,
        'title' => 'Stream Title',
        'started_at' => '2025-01-01T11:00:00+11:00',
        'ended_at' => null,
    ];

    $payload = LivestreamStatusUpdatedPayload::fromArray(
        $data,
        KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        1
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->broadcaster->username)->toBe('broadcaster')
        ->and($payload->isLive)->toBeTrue()
        ->and($payload->title)->toBe('Stream Title')
        ->and($payload->startedAt)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($payload->endedAt)->toBeNull();
});

test('can create LivestreamStatusUpdatedPayload from array for stream end', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'is_live' => false,
        'title' => 'Stream Title',
        'started_at' => '2025-01-01T11:00:00+11:00',
        'ended_at' => '2025-01-01T15:00:00+11:00',
    ];

    $payload = LivestreamStatusUpdatedPayload::fromArray(
        $data,
        KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        1
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->broadcaster->username)->toBe('broadcaster')
        ->and($payload->isLive)->toBeFalse()
        ->and($payload->title)->toBe('Stream Title')
        ->and($payload->startedAt)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($payload->endedAt)->toBeInstanceOf(DateTimeImmutable::class);
});

test('can serialize LivestreamStatusUpdatedPayload to array for stream start', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $startedAt = new DateTimeImmutable('2025-01-01T11:00:00+11:00');

    $payload = new LivestreamStatusUpdatedPayload(
        eventType: KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        eventVersion: 1,
        broadcaster: $broadcaster,
        isLive: true,
        title: 'Stream Title',
        startedAt: $startedAt,
        endedAt: null
    );

    $serialized = $payload->jsonSerialize();

    expect($serialized)->toBeArray()
        ->and($serialized['broadcaster'])->toBeArray()
        ->and($serialized['is_live'])->toBeTrue()
        ->and($serialized['title'])->toBe('Stream Title')
        ->and($serialized['started_at'])->toBeString()
        ->and($serialized['ended_at'])->toBeNull();
});

test('can serialize LivestreamStatusUpdatedPayload to array for stream end', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $startedAt = new DateTimeImmutable('2025-01-01T11:00:00+11:00');
    $endedAt = new DateTimeImmutable('2025-01-01T15:00:00+11:00');

    $payload = new LivestreamStatusUpdatedPayload(
        eventType: KickWebhookEventTypeEnum::LivestreamStatusUpdated,
        eventVersion: 1,
        broadcaster: $broadcaster,
        isLive: false,
        title: 'Stream Title',
        startedAt: $startedAt,
        endedAt: $endedAt
    );

    $serialized = $payload->jsonSerialize();

    expect($serialized)->toBeArray()
        ->and($serialized['broadcaster'])->toBeArray()
        ->and($serialized['is_live'])->toBeFalse()
        ->and($serialized['title'])->toBe('Stream Title')
        ->and($serialized['started_at'])->toBeString()
        ->and($serialized['ended_at'])->toBeString();
});

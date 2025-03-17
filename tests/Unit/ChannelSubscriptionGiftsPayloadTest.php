<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelSubscriptionGiftsPayload;

test('can create ChannelSubscriptionGiftsPayload with constructor', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $gifter = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 987654321,
        username: 'gifter',
        isVerified: false,
        profilePicture: 'https://example.com/gifter.jpg',
        channelSlug: 'gifter_channel'
    );

    $giftee1 = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 111222333,
        username: 'giftee1',
        isVerified: false,
        profilePicture: 'https://example.com/giftee1.jpg',
        channelSlug: 'giftee1_channel'
    );

    $giftee2 = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 444555666,
        username: 'giftee2',
        isVerified: false,
        profilePicture: 'https://example.com/giftee2.jpg',
        channelSlug: 'giftee2_channel'
    );

    $createdAt = new DateTimeImmutable('2025-01-14T16:08:06Z');

    $payload = new ChannelSubscriptionGiftsPayload(
        eventType: KickWebhookEventTypeEnum::ChannelSubscriptionGifts,
        eventVersion: 1,
        broadcaster: $broadcaster,
        gifter: $gifter,
        giftees: [$giftee1, $giftee2],
        createdAt: $createdAt
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionGifts)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBe($broadcaster)
        ->and($payload->gifter)->toBe($gifter)
        ->and($payload->giftees)->toHaveCount(2)
        ->and($payload->giftees[0])->toBe($giftee1)
        ->and($payload->giftees[1])->toBe($giftee2)
        ->and($payload->createdAt)->toBe($createdAt);
});

test('can create ChannelSubscriptionGiftsPayload from array', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'gifter' => [
            'is_anonymous' => false,
            'user_id' => 987654321,
            'username' => 'gifter',
            'is_verified' => false,
            'profile_picture' => 'https://example.com/gifter.jpg',
            'channel_slug' => 'gifter_channel',
        ],
        'giftees' => [
            [
                'is_anonymous' => false,
                'user_id' => 111222333,
                'username' => 'giftee1',
                'is_verified' => false,
                'profile_picture' => 'https://example.com/giftee1.jpg',
                'channel_slug' => 'giftee1_channel',
            ],
            [
                'is_anonymous' => false,
                'user_id' => 444555666,
                'username' => 'giftee2',
                'is_verified' => false,
                'profile_picture' => 'https://example.com/giftee2.jpg',
                'channel_slug' => 'giftee2_channel',
            ],
        ],
        'created_at' => '2025-01-14T16:08:06Z',
    ];

    $payload = ChannelSubscriptionGiftsPayload::fromArray(
        $data,
        KickWebhookEventTypeEnum::ChannelSubscriptionGifts,
        1
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionGifts)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->broadcaster->username)->toBe('broadcaster')
        ->and($payload->gifter)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->gifter->username)->toBe('gifter')
        ->and($payload->giftees)->toHaveCount(2)
        ->and($payload->giftees[0])->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->giftees[0]->username)->toBe('giftee1')
        ->and($payload->giftees[1])->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->giftees[1]->username)->toBe('giftee2')
        ->and($payload->createdAt)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($payload->createdAt->format('Y-m-d\TH:i:s\Z'))->toBe('2025-01-14T16:08:06Z');
});

test('can create ChannelSubscriptionGiftsPayload with anonymous gifter', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'gifter' => [
            'is_anonymous' => true,
            'user_id' => null,
            'username' => null,
            'is_verified' => null,
            'profile_picture' => null,
            'channel_slug' => null,
        ],
        'giftees' => [
            [
                'is_anonymous' => false,
                'user_id' => 111222333,
                'username' => 'giftee1',
                'is_verified' => false,
                'profile_picture' => 'https://example.com/giftee1.jpg',
                'channel_slug' => 'giftee1_channel',
            ],
        ],
        'created_at' => '2025-01-14T16:08:06Z',
    ];

    $payload = ChannelSubscriptionGiftsPayload::fromArray(
        $data,
        KickWebhookEventTypeEnum::ChannelSubscriptionGifts,
        1
    );

    expect($payload->gifter->isAnonymous)->toBeTrue()
        ->and($payload->gifter->userId)->toBeNull()
        ->and($payload->gifter->username)->toBeNull();
});

test('can serialize ChannelSubscriptionGiftsPayload to array', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $gifter = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 987654321,
        username: 'gifter',
        isVerified: false,
        profilePicture: 'https://example.com/gifter.jpg',
        channelSlug: 'gifter_channel'
    );

    $giftee1 = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 111222333,
        username: 'giftee1',
        isVerified: false,
        profilePicture: 'https://example.com/giftee1.jpg',
        channelSlug: 'giftee1_channel'
    );

    $createdAt = new DateTimeImmutable('2025-01-14T16:08:06Z');

    $payload = new ChannelSubscriptionGiftsPayload(
        eventType: KickWebhookEventTypeEnum::ChannelSubscriptionGifts,
        eventVersion: 1,
        broadcaster: $broadcaster,
        gifter: $gifter,
        giftees: [$giftee1],
        createdAt: $createdAt
    );

    $serialized = $payload->jsonSerialize();

    expect($serialized)->toBeArray()
        ->and($serialized['broadcaster'])->toBeArray()
        ->and($serialized['gifter'])->toBeArray()
        ->and($serialized['giftees'])->toBeArray()->toHaveCount(1)
        ->and($serialized['created_at'])->toBe('2025-01-14T16:08:06Z');
});

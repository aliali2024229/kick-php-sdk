<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookUserEntity;
use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelFollowedPayload;

test('can create ChannelFollowedPayload with constructor', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $follower = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 987654321,
        username: 'follower',
        isVerified: false,
        profilePicture: 'https://example.com/follower.jpg',
        channelSlug: 'follower_channel'
    );

    $payload = new ChannelFollowedPayload(
        eventType: KickWebhookEventTypeEnum::ChannelFollowed,
        eventVersion: 1,
        broadcaster: $broadcaster,
        follower: $follower
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelFollowed)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBe($broadcaster)
        ->and($payload->follower)->toBe($follower);
});

test('can create ChannelFollowedPayload from array', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'follower' => [
            'is_anonymous' => false,
            'user_id' => 987654321,
            'username' => 'follower',
            'is_verified' => false,
            'profile_picture' => 'https://example.com/follower.jpg',
            'channel_slug' => 'follower_channel',
        ],
    ];

    $payload = ChannelFollowedPayload::fromArray(
        $data,
        KickWebhookEventTypeEnum::ChannelFollowed,
        1
    );

    expect($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelFollowed)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->broadcaster)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->broadcaster->username)->toBe('broadcaster')
        ->and($payload->follower)->toBeInstanceOf(KickWebhookUserEntity::class)
        ->and($payload->follower->username)->toBe('follower');
});

test('can serialize ChannelFollowedPayload to array', function () {
    $broadcaster = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 123456789,
        username: 'broadcaster',
        isVerified: true,
        profilePicture: 'https://example.com/broadcaster.jpg',
        channelSlug: 'broadcaster_channel'
    );

    $follower = new KickWebhookUserEntity(
        isAnonymous: false,
        userId: 987654321,
        username: 'follower',
        isVerified: false,
        profilePicture: 'https://example.com/follower.jpg',
        channelSlug: 'follower_channel'
    );

    $payload = new ChannelFollowedPayload(
        eventType: KickWebhookEventTypeEnum::ChannelFollowed,
        eventVersion: 1,
        broadcaster: $broadcaster,
        follower: $follower
    );

    $serialized = $payload->jsonSerialize();

    expect($serialized)->toBeArray()
        ->and($serialized['broadcaster'])->toBeArray()
        ->and($serialized['follower'])->toBeArray();
});

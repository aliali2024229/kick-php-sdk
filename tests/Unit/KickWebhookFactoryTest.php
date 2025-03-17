<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\KickWebhookFactory;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelFollowedPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelSubscriptionGiftsPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelSubscriptionNewPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelSubscriptionRenewalPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChatMessageSentPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\LivestreamStatusUpdatedPayload;

test('can create ChatMessageSentPayload from request', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'sender' => [
            'is_anonymous' => false,
            'user_id' => 987654321,
            'username' => 'sender',
            'is_verified' => false,
            'profile_picture' => 'https://example.com/sender.jpg',
            'channel_slug' => 'sender_channel',
        ],
        'message_id' => 'msg_123',
        'content' => 'Hello world!',
        'emotes' => [],
    ];

    $headers = [
        'Kick-Event-Type' => 'chat.message.sent',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(ChatMessageSentPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChatMessageSent)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->messageId)->toBe('msg_123')
        ->and($payload->content)->toBe('Hello world!');
});

test('can create ChannelFollowedPayload from request', function () {
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

    $headers = [
        'Kick-Event-Type' => 'channel.followed',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(ChannelFollowedPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelFollowed)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->follower->username)->toBe('follower');
});

test('can create ChannelSubscriptionRenewalPayload from request', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'subscriber' => [
            'is_anonymous' => false,
            'user_id' => 987654321,
            'username' => 'subscriber',
            'is_verified' => false,
            'profile_picture' => 'https://example.com/subscriber.jpg',
            'channel_slug' => 'subscriber_channel',
        ],
        'duration' => 3,
        'created_at' => '2025-01-14T16:08:06Z',
    ];

    $headers = [
        'Kick-Event-Type' => 'channel.subscription.renewal',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(ChannelSubscriptionRenewalPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionRenewal)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->subscriber->username)->toBe('subscriber')
        ->and($payload->duration)->toBe(3);
});

test('can create ChannelSubscriptionGiftsPayload from request', function () {
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

    $headers = [
        'Kick-Event-Type' => 'channel.subscription.gifts',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(ChannelSubscriptionGiftsPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionGifts)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->gifter->username)->toBe('gifter')
        ->and($payload->giftees)->toHaveCount(2)
        ->and($payload->giftees[0]->username)->toBe('giftee1')
        ->and($payload->giftees[1]->username)->toBe('giftee2');
});

test('can create ChannelSubscriptionNewPayload from request', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
        'subscriber' => [
            'is_anonymous' => false,
            'user_id' => 987654321,
            'username' => 'subscriber',
            'is_verified' => false,
            'profile_picture' => 'https://example.com/subscriber.jpg',
            'channel_slug' => 'subscriber_channel',
        ],
        'duration' => 1,
        'created_at' => '2025-01-14T16:08:06Z',
    ];

    $headers = [
        'Kick-Event-Type' => 'channel.subscription.new',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(ChannelSubscriptionNewPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionNew)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->subscriber->username)->toBe('subscriber')
        ->and($payload->duration)->toBe(1);
});

test('can create LivestreamStatusUpdatedPayload from request', function () {
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

    $headers = [
        'Kick-Event-Type' => 'livestream.status.updated',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeInstanceOf(LivestreamStatusUpdatedPayload::class)
        ->and($payload->eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated)
        ->and($payload->eventVersion)->toBe(1)
        ->and($payload->isLive)->toBeTrue()
        ->and($payload->title)->toBe('Stream Title')
        ->and($payload->endedAt)->toBeNull();
});

test('returns null for unknown event type', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
    ];

    $headers = [
        'Kick-Event-Type' => 'unknown.event.type',
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeNull();
});

test('returns null for missing event type header', function () {
    $data = [
        'broadcaster' => [
            'is_anonymous' => false,
            'user_id' => 123456789,
            'username' => 'broadcaster',
            'is_verified' => true,
            'profile_picture' => 'https://example.com/broadcaster.jpg',
            'channel_slug' => 'broadcaster_channel',
        ],
    ];

    $headers = [
        'Kick-Event-Version' => '1',
    ];

    $payload = KickWebhookFactory::createFromRequest($headers, $data);

    expect($payload)->toBeNull();
});

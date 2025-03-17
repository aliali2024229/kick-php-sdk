<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;

test('can get event type from header value', function () {
    $eventType = KickWebhookEventTypeEnum::fromHeader('chat.message.sent');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::ChatMessageSent);

    $eventType = KickWebhookEventTypeEnum::fromHeader('channel.followed');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::ChannelFollowed);

    $eventType = KickWebhookEventTypeEnum::fromHeader('channel.subscription.renewal');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionRenewal);

    $eventType = KickWebhookEventTypeEnum::fromHeader('channel.subscription.gifts');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionGifts);

    $eventType = KickWebhookEventTypeEnum::fromHeader('channel.subscription.new');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::ChannelSubscriptionNew);

    $eventType = KickWebhookEventTypeEnum::fromHeader('livestream.status.updated');
    expect($eventType)->toBe(KickWebhookEventTypeEnum::LivestreamStatusUpdated);
});

test('returns null for unknown event type', function () {
    $eventType = KickWebhookEventTypeEnum::fromHeader('unknown.event.type');
    expect($eventType)->toBeNull();
});

test('returns null for null header value', function () {
    $eventType = KickWebhookEventTypeEnum::fromHeader(null);
    expect($eventType)->toBeNull();
});

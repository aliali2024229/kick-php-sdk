<?php

use DanielHe4rt\KickSDK\Events\Webhooks\Entities\KickWebhookEmoteEntity;

test('can create KickWebhookEmoteEntity with constructor', function () {
    $positions = [
        ['s' => 0, 'e' => 5],
        ['s' => 10, 'e' => 15],
    ];

    $entity = new KickWebhookEmoteEntity(
        emoteId: '12345',
        positions: $positions
    );

    expect($entity->emoteId)->toBe('12345')
        ->and($entity->positions)->toBe($positions);
});

test('can create KickWebhookEmoteEntity from array', function () {
    $data = [
        'emote_id' => '12345',
        'positions' => [
            ['s' => 0, 'e' => 5],
            ['s' => 10, 'e' => 15],
        ],
    ];

    $entity = KickWebhookEmoteEntity::fromArray($data);

    expect($entity->emoteId)->toBe('12345')
        ->and($entity->positions)->toBe($data['positions']);
});

test('can create KickWebhookEmoteEntity with empty positions', function () {
    $data = [
        'emote_id' => '12345',
    ];

    $entity = KickWebhookEmoteEntity::fromArray($data);

    expect($entity->emoteId)->toBe('12345')
        ->and($entity->positions)->toBe([]);
});

test('can serialize KickWebhookEmoteEntity to array', function () {
    $positions = [
        ['s' => 0, 'e' => 5],
        ['s' => 10, 'e' => 15],
    ];

    $entity = new KickWebhookEmoteEntity(
        emoteId: '12345',
        positions: $positions
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'emote_id' => '12345',
        'positions' => $positions,
    ]);
});

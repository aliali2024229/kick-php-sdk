<?php

use Danielhe4rt\KickSDK\Chat\Entities\KickChatMessageEntity;

test('can create KickChatMessageEntity with constructor', function () {
    $entity = new KickChatMessageEntity(
        isSent: true,
        messageId: 'abc123'
    );

    expect($entity->isSent)->toBeTrue()
        ->and($entity->messageId)->toBe('abc123');
});

test('can create KickChatMessageEntity from array', function () {
    $data = [
        'is_sent' => true,
        'message_id' => 'abc123'
    ];

    $entity = KickChatMessageEntity::fromArray($data);

    expect($entity->isSent)->toBeTrue()
        ->and($entity->messageId)->toBe('abc123');
});

test('can serialize KickChatMessageEntity to array', function () {
    $entity = new KickChatMessageEntity(
        isSent: true,
        messageId: 'abc123'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'is_sent' => true,
        'message_id' => 'abc123'
    ]);
}); 
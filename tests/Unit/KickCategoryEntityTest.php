<?php

use Danielhe4rt\KickSDK\Streams\Entities\KickCategoryEntity;

test('can create KickCategoryEntity with constructor', function () {
    $entity = new KickCategoryEntity(
        id: 123,
        name: 'Just Chatting',
        thumbnail: 'https://example.com/thumbnail.jpg'
    );

    expect($entity->id)->toBe(123)
        ->and($entity->name)->toBe('Just Chatting')
        ->and($entity->thumbnail)->toBe('https://example.com/thumbnail.jpg');
});

test('can create KickCategoryEntity from array', function () {
    $data = [
        'id' => 123,
        'name' => 'Just Chatting',
        'thumbnail' => 'https://example.com/thumbnail.jpg'
    ];

    $entity = KickCategoryEntity::fromArray($data);

    expect($entity->id)->toBe(123)
        ->and($entity->name)->toBe('Just Chatting')
        ->and($entity->thumbnail)->toBe('https://example.com/thumbnail.jpg');
});

test('can serialize KickCategoryEntity to array', function () {
    $entity = new KickCategoryEntity(
        id: 123,
        name: 'Just Chatting',
        thumbnail: 'https://example.com/thumbnail.jpg'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'id' => 123,
        'name' => 'Just Chatting',
        'thumbnail' => 'https://example.com/thumbnail.jpg'
    ]);
}); 
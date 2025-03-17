<?php

use DanielHe4rt\KickSDK\Streams\DTOs\UpdateChannelDTO;

test('can create UpdateChannelDTO with both parameters', function () {
    $dto = new UpdateChannelDTO(
        categoryId: 123,
        streamTitle: 'Test Stream Title'
    );

    expect($dto->categoryId)->toBe(123)
        ->and($dto->streamTitle)->toBe('Test Stream Title');
});

test('can create UpdateChannelDTO with only categoryId', function () {
    $dto = new UpdateChannelDTO(
        categoryId: 123
    );

    expect($dto->categoryId)->toBe(123)
        ->and($dto->streamTitle)->toBeNull();
});

test('can create UpdateChannelDTO with only streamTitle', function () {
    $dto = new UpdateChannelDTO(
        streamTitle: 'Test Stream Title'
    );

    expect($dto->categoryId)->toBeNull()
        ->and($dto->streamTitle)->toBe('Test Stream Title');
});

test('can create empty UpdateChannelDTO', function () {
    $dto = new UpdateChannelDTO;

    expect($dto->categoryId)->toBeNull()
        ->and($dto->streamTitle)->toBeNull();
});

test('can serialize UpdateChannelDTO with both parameters', function () {
    $dto = new UpdateChannelDTO(
        categoryId: 123,
        streamTitle: 'Test Stream Title'
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'category_id' => 123,
        'stream_title' => 'Test Stream Title',
    ]);
});

test('can serialize UpdateChannelDTO with only categoryId', function () {
    $dto = new UpdateChannelDTO(
        categoryId: 123
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'category_id' => 123,
    ]);
});

test('can serialize UpdateChannelDTO with only streamTitle', function () {
    $dto = new UpdateChannelDTO(
        streamTitle: 'Test Stream Title'
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'stream_title' => 'Test Stream Title',
    ]);
});

test('can serialize empty UpdateChannelDTO', function () {
    $dto = new UpdateChannelDTO;

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([]);
});

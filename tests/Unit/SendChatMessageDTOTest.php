<?php

use Danielhe4rt\KickSDK\Chat\DTOs\SendChatMessageDTO;
use Danielhe4rt\KickSDK\Chat\KickMessageTypeEnum;

test('can create SendChatMessageDTO with default type', function () {
    $dto = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!'
    );

    expect($dto->broadcaster_user_id)->toBe(123)
        ->and($dto->content)->toBe('Hello, world!')
        ->and($dto->type)->toBe(KickMessageTypeEnum::User);
});

test('can create SendChatMessageDTO with user type', function () {
    $dto = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!',
        type: KickMessageTypeEnum::User
    );

    expect($dto->broadcaster_user_id)->toBe(123)
        ->and($dto->content)->toBe('Hello, world!')
        ->and($dto->type)->toBe(KickMessageTypeEnum::User);
});

test('can create SendChatMessageDTO with bot type', function () {
    $dto = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!',
        type: KickMessageTypeEnum::Bot
    );

    expect($dto->broadcaster_user_id)->toBe(123)
        ->and($dto->content)->toBe('Hello, world!')
        ->and($dto->type)->toBe(KickMessageTypeEnum::Bot);
});

test('can create SendChatMessageDTO using make method', function () {
    $dto = SendChatMessageDTO::make(
        broadcaster_user_id: 123,
        content: 'Hello, world!',
    );

    expect($dto->broadcaster_user_id)->toBe(123)
        ->and($dto->content)->toBe('Hello, world!')
        ->and($dto->type)->toBe(KickMessageTypeEnum::User);
});

test('can serialize SendChatMessageDTO to array', function () {
    $dto = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!',
        type: KickMessageTypeEnum::User
    );

    $serialized = $dto->jsonSerialize();

    expect($serialized)->toBe([
        'broadcaster_user_id' => 123,
        'content' => 'Hello, world!',
        'type' => 'user'
    ]);
});

test('throws exception when content exceeds 500 characters', function () {
    $longContent = str_repeat('a', 501);

    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Message content cannot exceed 500 characters');

    new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: $longContent
    );
});

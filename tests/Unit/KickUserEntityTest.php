<?php

use Danielhe4rt\KickSDK\Users\Entities\KickUserEntity;

test('can create KickUserEntity with constructor with email', function () {
    $entity = new KickUserEntity(
        userId: 123,
        username: 'testuser',
        profile_picture: 'https://example.com/profile.jpg',
        email: 'test@example.com'
    );

    expect($entity->userId)->toBe(123)
        ->and($entity->username)->toBe('testuser')
        ->and($entity->profile_picture)->toBe('https://example.com/profile.jpg')
        ->and($entity->email)->toBe('test@example.com');
});

test('can create KickUserEntity with constructor without email', function () {
    $entity = new KickUserEntity(
        userId: 123,
        username: 'testuser',
        profile_picture: 'https://example.com/profile.jpg'
    );

    expect($entity->userId)->toBe(123)
        ->and($entity->username)->toBe('testuser')
        ->and($entity->profile_picture)->toBe('https://example.com/profile.jpg')
        ->and($entity->email)->toBeNull();
});

test('can create KickUserEntity from array with email', function () {
    $data = [
        'user_id' => 123,
        'name' => 'testuser',
        'profile_picture' => 'https://example.com/profile.jpg',
        'email' => 'test@example.com'
    ];

    $entity = KickUserEntity::fromArray($data);

    expect($entity->userId)->toBe(123)
        ->and($entity->username)->toBe('testuser')
        ->and($entity->profile_picture)->toBe('https://example.com/profile.jpg')
        ->and($entity->email)->toBe('test@example.com');
});

test('can create KickUserEntity from array without email', function () {
    $data = [
        'user_id' => 123,
        'name' => 'testuser',
        'profile_picture' => 'https://example.com/profile.jpg'
    ];

    $entity = KickUserEntity::fromArray($data);

    expect($entity->userId)->toBe(123)
        ->and($entity->username)->toBe('testuser')
        ->and($entity->profile_picture)->toBe('https://example.com/profile.jpg')
        ->and($entity->email)->toBeNull();
});

test('can serialize KickUserEntity with email', function () {
    $entity = new KickUserEntity(
        userId: 123,
        username: 'testuser',
        profile_picture: 'https://example.com/profile.jpg',
        email: 'test@example.com'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'user_id' => 123,
        'name' => 'testuser',
        'profile_picture' => 'https://example.com/profile.jpg',
        'email' => 'test@example.com'
    ]);
});

test('can serialize KickUserEntity without email', function () {
    $entity = new KickUserEntity(
        userId: 123,
        username: 'testuser',
        profile_picture: 'https://example.com/profile.jpg'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'user_id' => 123,
        'name' => 'testuser',
        'profile_picture' => 'https://example.com/profile.jpg',
        'email' => null
    ]);
}); 
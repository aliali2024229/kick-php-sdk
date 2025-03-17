<?php

use Danielhe4rt\KickSDK\Users\Entities\KickUserEntity;
use Danielhe4rt\KickSDK\Users\KickUserResource;
use Danielhe4rt\KickSDK\Users\UserException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

test('can create a KickUserEntity from an array', function () {

    $data = [
        'user_id' => 123,
        'name' => 'testuser',
        'profile_picture' => 'http://example.com/profile.jpg',
        'email' => null,
    ];

    $userEntity = KickUserEntity::fromArray($data);
    expect($userEntity)->toBeInstanceOf(KickUserEntity::class)
        ->and($userEntity->userId)->toBe(123)
        ->and($userEntity->username)->toBe('testuser')
        ->and($userEntity->profile_picture)->toBe('http://example.com/profile.jpg')
        ->and($userEntity->email)->toBeNull();
});

test('can fetch a user by ID', function () {
    $userId = 12345;
    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [], json_encode([
            'data' => [
                [
                    'user_id' => $userId,
                    'name' => 'testuser',
                    'profile_picture' => 'http://example.com/profile.jpg',
                    'email' => null,
                ],
            ],
            'message' => 'text',
        ], JSON_THROW_ON_ERROR)),
    ]);

    $client = new GuzzleHttp\Client(['handler' => $mockHandler]);

    $userResource = new KickUserResource(
        client: $client,
        accessToken: 'valid_access_token',
    );

    $actualResponse = $userResource->fetchUserById($userId);

    expect($actualResponse)->toBeInstanceOf(KickUserEntity::class)
        ->and($actualResponse->userId)->toBe($userId)
        ->and($actualResponse->username)->toBe('testuser')
        ->and($actualResponse->profile_picture)->toBe('http://example.com/profile.jpg')
        ->and($actualResponse->email)->toBeNull();
});

test('can fetch a users by ID', function () {
    $userIds = [12345, 67890];
    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [], json_encode([
            'data' => [
                [
                    'user_id' => $userIds[0],
                    'name' => 'testuser',
                    'profile_picture' => 'http://example.com/profile.jpg',
                    'email' => null,
                ],
                [
                    'user_id' => $userIds[1],
                    'name' => 'testuser',
                    'profile_picture' => 'http://example.com/profile.jpg',
                    'email' => null,
                ],
            ],
            'message' => 'text',
        ], JSON_THROW_ON_ERROR)),
    ]);

    $client = new GuzzleHttp\Client(['handler' => $mockHandler]);

    $userResource = new KickUserResource(
        client: $client,
        accessToken: 'valid_access_token',
    );

    $actualResponse = $userResource->fetchUsersById($userIds);

    expect($actualResponse)->toBeArray();

    foreach ($actualResponse as $idx => $user) {
        expect($user)->toBeInstanceOf(KickUserEntity::class)
            ->and($user->userId)->toBe($userIds[$idx])
            ->and($user->username)->toBe('testuser')
            ->and($user->profile_picture)->toBe('http://example.com/profile.jpg')
            ->and($user->email)->toBeNull();
    }
});

test('throw exception on users me', function () {
    $userId = 12345;
    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR)
    ]);

    $client = new GuzzleHttp\Client(['handler' => $mockHandler]);

    $userResource = new KickUserResource(
        client: $client,
        accessToken: 'valid_access_token',
    );


    $this->expectException(UserException::class);
    $actualResponse = $userResource->fetchUserById($userId);
});
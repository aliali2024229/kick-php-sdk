<?php

use DanielHe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use DanielHe4rt\KickSDK\Streams\KickStreamFactory;
use DanielHe4rt\KickSDK\Streams\KickStreamResource;
use GuzzleHttp\Client;

test('can create KickStreamResource with default client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'channel:read',
        token_type: 'Bearer'
    );

    $resource = KickStreamFactory::make($accessToken);

    expect($resource)->toBeInstanceOf(KickStreamResource::class);
    expect($resource->accessToken)->toBe('test_access_token');
});

test('can create KickStreamResource with custom client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'channel:read',
        token_type: 'Bearer'
    );

    $customClient = new Client(['base_uri' => 'https://example.com']);
    $resource = KickStreamFactory::make($accessToken, $customClient);

    expect($resource)->toBeInstanceOf(KickStreamResource::class)
        ->and($resource->accessToken)->toBe('test_access_token')
        ->and($resource->client)->toBe($customClient);
});

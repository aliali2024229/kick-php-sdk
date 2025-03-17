<?php

use Danielhe4rt\KickSDK\Events\KickEventsFactory;
use Danielhe4rt\KickSDK\Events\KickEventsResource;
use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

test('can create KickEventsResource with default client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'events:read events:write',
        token_type: 'Bearer'
    );

    $resource = KickEventsFactory::make($accessToken);

    expect($resource)->toBeInstanceOf(KickEventsResource::class);
    expect($resource->accessToken)->toBe('test_access_token');
});

test('can create KickEventsResource with custom client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'events:read events:write',
        token_type: 'Bearer'
    );

    $customClient = new Client(['base_uri' => 'https://example.com']);
    $resource = KickEventsFactory::make($accessToken, $customClient);

    expect($resource)->toBeInstanceOf(KickEventsResource::class)
        ->and($resource->accessToken)->toBe('test_access_token')
        ->and($resource->client)->toBe($customClient);
}); 
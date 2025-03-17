<?php

use Danielhe4rt\KickSDK\Chat\KickChatFactory;
use Danielhe4rt\KickSDK\Chat\KickChatResource;
use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

test('can create KickChatResource with default client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'chat:write',
        token_type: 'Bearer'
    );

    $resource = KickChatFactory::make($accessToken);

    expect($resource)->toBeInstanceOf(KickChatResource::class);
    expect($resource->accessToken)->toBe('test_access_token');
});

test('can create KickChatResource with custom client', function () {
    $accessToken = new KickAccessTokenEntity(
        accessToken: 'test_access_token',
        expires_in: 3600,
        refreshToken: 'test_refresh_token',
        scope: 'chat:write',
        token_type: 'Bearer'
    );

    $customClient = new Client(['base_uri' => 'https://example.com']);
    $resource = KickChatFactory::make($accessToken, $customClient);

    expect($resource)->toBeInstanceOf(KickChatResource::class)
        ->and($resource->accessToken)->toBe('test_access_token')
        ->and($resource->client)->toBe($customClient);
}); 
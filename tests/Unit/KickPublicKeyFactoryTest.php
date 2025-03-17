<?php

use Danielhe4rt\KickSDK\PublicKey\KickPublicKeyFactory;
use Danielhe4rt\KickSDK\PublicKey\KickPublicKeyResource;
use GuzzleHttp\Client;

test('can create KickPublicKeyResource with default client', function () {
    $resource = KickPublicKeyFactory::make();

    expect($resource)->toBeInstanceOf(KickPublicKeyResource::class);
});

test('can create KickPublicKeyResource with custom client', function () {
    $customClient = new Client(['base_uri' => 'https://example.com']);
    $resource = KickPublicKeyFactory::make($customClient);

    expect($resource)->toBeInstanceOf(KickPublicKeyResource::class)
        ->and($resource->client)->toBe($customClient);
}); 
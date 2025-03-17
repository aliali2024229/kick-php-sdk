<?php

use DanielHe4rt\KickSDK\Chat\KickChatResource;
use DanielHe4rt\KickSDK\Events\KickEventsResource;
use DanielHe4rt\KickSDK\KickClient;
use DanielHe4rt\KickSDK\OAuth\KickOAuthResource;
use DanielHe4rt\KickSDK\PublicKey\KickPublicKeyResource;
use DanielHe4rt\KickSDK\Streams\KickStreamResource;
use DanielHe4rt\KickSDK\Users\KickUserResource;

test('can create KickClient with constructor', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    expect($client->clientId)->toBe('test_client_id')
        ->and($client->clientSecret)->toBe('test_client_secret')
        ->and($client->client)->toBeInstanceOf(GuzzleHttp\Client::class);
});

test('can get OAuth resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $oauthResource = $client->oauth();

    expect($oauthResource)->toBeInstanceOf(KickOAuthResource::class);
});

test('can get Users resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $usersResource = $client->users('test_access_token');

    expect($usersResource)->toBeInstanceOf(KickUserResource::class)
        ->and($usersResource->accessToken)->toBe('test_access_token');
});

test('can get Streams resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $streamsResource = $client->streams('test_access_token');

    expect($streamsResource)->toBeInstanceOf(KickStreamResource::class)
        ->and($streamsResource->accessToken)->toBe('test_access_token');
});

test('can get Chat resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $chatResource = $client->chat('test_access_token');

    expect($chatResource)->toBeInstanceOf(KickChatResource::class)
        ->and($chatResource->accessToken)->toBe('test_access_token');
});

test('can get Public Key resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $publicKeyResource = $client->publicKey();

    expect($publicKeyResource)->toBeInstanceOf(KickPublicKeyResource::class);
});

test('can get Events resource', function () {
    $client = new KickClient(
        clientId: 'test_client_id',
        clientSecret: 'test_client_secret'
    );

    $eventsResource = $client->events('test_access_token');

    expect($eventsResource)->toBeInstanceOf(KickEventsResource::class)
        ->and($eventsResource->accessToken)->toBe('test_access_token');
});

<?php

use DanielHe4rt\KickSDK\KickClient;
use DanielHe4rt\KickSDK\OAuth\KickOAuthResource;

it('can create a KickClient instance', function () {
    $clientId = 'your-client-id';
    $clientSecret = 'your-client-secret';
    $kickClient = new KickClient($clientId, $clientSecret);

    expect($kickClient)->toBeInstanceOf(KickClient::class);
});

it('can get a KickOAuth Instance', function () {
    $clientId = 'your-client-id';
    $clientSecret = 'your-client-secret';
    $kickClient = new KickClient($clientId, $clientSecret);
    $oauth = $kickClient->oauth();
    expect($oauth)->toBeInstanceOf(KickOAuthResource::class);
});

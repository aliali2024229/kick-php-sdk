<?php

use Danielhe4rt\KickSDK\PublicKey\Entities\KickPublicKeyEntity;

test('can create KickPublicKeyEntity with constructor', function () {
    $entity = new KickPublicKeyEntity(
        publicKey: 'test-public-key'
    );

    expect($entity->publicKey)->toBe('test-public-key');
});

test('can create KickPublicKeyEntity from array', function () {
    $data = [
        'public_key' => 'test-public-key'
    ];

    $entity = KickPublicKeyEntity::fromArray($data);

    expect($entity->publicKey)->toBe('test-public-key');
});

test('can serialize KickPublicKeyEntity to array', function () {
    $entity = new KickPublicKeyEntity(
        publicKey: 'test-public-key'
    );

    $serialized = $entity->jsonSerialize();

    expect($serialized)->toBe([
        'public_key' => 'test-public-key'
    ]);
}); 
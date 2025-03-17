<?php

use DanielHe4rt\KickSDK\Events\Webhooks\KickWebhookVerifier;

test('can create verifier with constructor', function () {
    $verifier = new KickWebhookVerifier('test_public_key');
    expect($verifier)->toBeInstanceOf(KickWebhookVerifier::class);
});

test('verify returns false for invalid signature', function () {
    $verifier = new KickWebhookVerifier('invalid_key');
    $result = $verifier->verify('invalid_signature', 'payload');
    expect($result)->toBeFalse();
});

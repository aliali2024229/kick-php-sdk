<?php

use Danielhe4rt\KickSDK\PublicKey\KickPublicKeyException;

test('can create retrieval failed exception', function () {
    $exception = new \Exception('Test error message', 500);
    $publicKeyException = KickPublicKeyException::retrievalFailed($exception);

    expect($publicKeyException)->toBeInstanceOf(KickPublicKeyException::class)
        ->and($publicKeyException->getMessage())->toContain('[Kick Public Key Retrieval Failed]')
        ->and($publicKeyException->getMessage())->toContain('Test error message')
        ->and($publicKeyException->getCode())->toBe(500);
}); 
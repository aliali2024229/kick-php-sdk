<?php

use DanielHe4rt\KickSDK\PublicKey\Entities\KickPublicKeyEntity;
use DanielHe4rt\KickSDK\PublicKey\KickPublicKeyException;
use DanielHe4rt\KickSDK\PublicKey\KickPublicKeyResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

test('can get public key', function () {
    // Mock response data
    $responseData = [
        'data' => [
            'public_key' => 'test-public-key',
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickPublicKeyResource(
        client: new Client(['handler' => $mockHandler])
    );

    // Call the method
    $result = $resource->getPublicKey();

    // Assert response
    expect($result)->toBeInstanceOf(KickPublicKeyEntity::class)
        ->and($result->publicKey)->toBe('test-public-key');
});

test('throws exception when public key retrieval fails', function () {
    // Create mock handler with 500 Internal Server Error response
    $mockHandler = new MockHandler([
        new ClientException(
            'Server Error',
            new Request('GET', 'test'),
            new Response(
                HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                [],
                json_encode(['data' => [], 'message' => 'Server Error'], JSON_THROW_ON_ERROR)
            )
        ),
    ]);

    // Create resource with mock client
    $resource = new KickPublicKeyResource(
        client: new Client(['handler' => $mockHandler])
    );

    // Expect exception
    $this->expectException(KickPublicKeyException::class);
    $this->expectExceptionMessage('[Kick Public Key Retrieval Failed]');

    // Call the method
    $resource->getPublicKey();
});

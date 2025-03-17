<?php

use Danielhe4rt\KickSDK\Chat\DTOs\SendChatMessageDTO;
use Danielhe4rt\KickSDK\Chat\Entities\KickChatMessageEntity;
use Danielhe4rt\KickSDK\Chat\KickChatException;
use Danielhe4rt\KickSDK\Chat\KickChatResource;
use Danielhe4rt\KickSDK\Chat\KickMessageTypeEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

test('can send a chat message', function () {
    // Mock response data
    $responseData = [
        'data' => [
            'is_sent' => true,
            'message_id' => 'abc123'
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickChatResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $messageDTO = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!',
        type: KickMessageTypeEnum::User
    );

    // Call the method
    $result = $resource->sendMessage($messageDTO);

    // Assert response
    expect($result)->toBeInstanceOf(KickChatMessageEntity::class)
        ->and($result->isSent)->toBeTrue()
        ->and($result->messageId)->toBe('abc123');
});

test('throws exception when unauthorized', function () {
    // Create mock handler with 401 Unauthorized response
    $mockHandler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('POST', 'test'),
            new Response(
                HttpResponse::HTTP_UNAUTHORIZED, 
                [], 
                json_encode(['data' => [], 'message' => 'Unauthorized'], JSON_THROW_ON_ERROR)
            )
        )
    ]);

    // Create resource with mock client
    $resource = new KickChatResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'invalid_access_token'
    );

    // Create DTO
    $messageDTO = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!'
    );

    // Expect exception
    $this->expectException(KickChatException::class);
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');
    
    // Call the method
    $resource->sendMessage($messageDTO);
});

test('throws exception when forbidden', function () {
    // Create mock handler with 403 Forbidden response
    $mockHandler = new MockHandler([
        new ClientException(
            'Forbidden',
            new Request('POST', 'test'),
            new Response(
                HttpResponse::HTTP_FORBIDDEN, 
                [], 
                json_encode(['data' => [], 'message' => 'Forbidden'], JSON_THROW_ON_ERROR)
            )
        )
    ]);

    // Create resource with mock client
    $resource = new KickChatResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $messageDTO = new SendChatMessageDTO(
        broadcaster_user_id: 123,
        content: 'Hello, world!'
    );

    // Expect exception
    $this->expectException(KickChatException::class);
    $this->expectExceptionMessage('You do not have permission to send messages to this channel');
    
    // Call the method
    $resource->sendMessage($messageDTO);
});

test('throws exception when channel not found', function () {
    // Create mock handler with 404 Not Found response
    $mockHandler = new MockHandler([
        new ClientException(
            'Not Found',
            new Request('POST', 'test'),
            new Response(
                HttpResponse::HTTP_NOT_FOUND, 
                [], 
                json_encode(['data' => [], 'message' => 'Channel not found'], JSON_THROW_ON_ERROR)
            )
        )
    ]);

    // Create resource with mock client
    $resource = new KickChatResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $messageDTO = new SendChatMessageDTO(
        broadcaster_user_id: 999,
        content: 'Hello, world!'
    );

    // Expect exception
    $this->expectException(KickChatException::class);
    $this->expectExceptionMessage('Channel with ID');
    
    // Call the method
    $resource->sendMessage($messageDTO);
}); 
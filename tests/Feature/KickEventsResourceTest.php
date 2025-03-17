<?php

use Danielhe4rt\KickSDK\Events\DTOs\CreateEventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\DTOs\EventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\Entities\KickEventSubscriptionEntity;
use Danielhe4rt\KickSDK\Events\Entities\KickEventSubscriptionResponseEntity;
use Danielhe4rt\KickSDK\Events\Enums\KickEventMethodEnum;
use Danielhe4rt\KickSDK\Events\Enums\KickEventTypeEnum;
use Danielhe4rt\KickSDK\Events\KickEventsException;
use Danielhe4rt\KickSDK\Events\KickEventsResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

test('can get event subscriptions', function () {
    // Mock response data
    $responseData = [
        'data' => [
            [
                'id' => 'sub_123',
                'app_id' => 'app_456',
                'broadcaster_user_id' => 789,
                'event' => 'chat.message.sent',
                'version' => 1,
                'method' => 'webhook',
                'created_at' => '2023-01-01T00:00:00Z',
                'updated_at' => '2023-01-01T00:00:00Z'
            ],
            [
                'id' => 'sub_456',
                'app_id' => 'app_456',
                'event' => 'channel.followed',
                'version' => 1,
                'method' => 'webhook',
                'created_at' => '2023-01-01T00:00:00Z',
                'updated_at' => '2023-01-01T00:00:00Z'
            ]
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Call the method
    $result = $resource->getSubscriptions();

    // Assert response
    expect($result)->toBeArray()->toHaveCount(2)
        ->and($result[0])->toBeInstanceOf(KickEventSubscriptionEntity::class)
        ->and($result[0]->id)->toBe('sub_123')
        ->and($result[0]->broadcasterUserId)->toBe(789)
        ->and($result[1])->toBeInstanceOf(KickEventSubscriptionEntity::class)
        ->and($result[1]->id)->toBe('sub_456')
        ->and($result[1]->broadcasterUserId)->toBeNull();
});

test('throws exception when unauthorized to get subscriptions', function () {
    // Create mock handler with 401 Unauthorized response
    $mockHandler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('GET', 'test'),
            new Response(
                HttpResponse::HTTP_UNAUTHORIZED,
                [],
                json_encode(['data' => [], 'message' => 'Unauthorized'], JSON_THROW_ON_ERROR)
            )
        )
    ]);

    // Create resource with mock client
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'invalid_access_token'
    );

    // Expect exception
    $this->expectException(KickEventsException::class);
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');

    // Call the method
    $resource->getSubscriptions();
});

test('can create event subscriptions', function () {
    // Mock response data
    $responseData = [
        'data' => [
            [
                'name' => 'chat.message.sent',
                'version' => 1,
                'subscription_id' => 'sub_123',
                'error' => null
            ],
            [
                'name' => 'channel.followed',
                'version' => 1,
                'subscription_id' => 'sub_456',
                'error' => null
            ]
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTOs
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1),
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChannelFollowed, version: 1)
    ];

    $dto = new CreateEventSubscriptionDTO(
        events: $events,
        method: KickEventMethodEnum::Webhook
    );

    // Call the method
    $result = $resource->subscribe($dto);

    // Assert response
    expect($result)->toBeArray()->toHaveCount(2)
        ->and($result[0])->toBeInstanceOf(KickEventSubscriptionResponseEntity::class)
        ->and($result[0]->name)->toBe('chat.message.sent')
        ->and($result[0]->subscriptionId)->toBe('sub_123')
        ->and($result[0]->isSuccessful())->toBeTrue()
        ->and($result[1])->toBeInstanceOf(KickEventSubscriptionResponseEntity::class)
        ->and($result[1]->name)->toBe('channel.followed')
        ->and($result[1]->subscriptionId)->toBe('sub_456')
        ->and($result[1]->isSuccessful())->toBeTrue();
});

test('throws exception when unauthorized to create subscriptions', function () {
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
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'invalid_access_token'
    );

    // Create DTOs
    $events = [
        new EventSubscriptionDTO(name: KickEventTypeEnum::ChatMessageSent, version: 1)
    ];

    $dto = new CreateEventSubscriptionDTO(
        events: $events
    );

    // Expect exception
    $this->expectException(KickEventsException::class);
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');

    // Call the method
    $resource->subscribe($dto);
});

test('can delete event subscriptions', function () {
    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_NO_CONTENT),
    ]);

    // Create resource with mock client
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Call the method
    $result = $resource->unsubscribe('sub_123');

    // Assert response
    expect($result)->toBeTrue();
});

test('throws exception when unauthorized to delete subscriptions', function () {
    // Create mock handler with 401 Unauthorized response
    $mockHandler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('DELETE', 'test'),
            new Response(
                HttpResponse::HTTP_UNAUTHORIZED,
                [],
                json_encode(['data' => [], 'message' => 'Unauthorized'], JSON_THROW_ON_ERROR)
            )
        )
    ]);

    // Create resource with mock client
    $resource = new KickEventsResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'invalid_access_token'
    );

    // Expect exception
    $this->expectException(KickEventsException::class);
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');

    // Call the method
    $resource->unsubscribe('sub_123');
}); 
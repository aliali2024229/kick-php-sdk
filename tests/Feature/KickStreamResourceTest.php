<?php

use DanielHe4rt\KickSDK\Streams\DTOs\UpdateChannelDTO;
use DanielHe4rt\KickSDK\Streams\Entities\KickCategoryEntity;
use DanielHe4rt\KickSDK\Streams\Entities\KickChannelEntity;
use DanielHe4rt\KickSDK\Streams\Entities\KickStreamEntity;
use DanielHe4rt\KickSDK\Streams\KickStreamException;
use DanielHe4rt\KickSDK\Streams\KickStreamResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

test('can i get my channel', function () {
    // Mock response data
    $responseData = [
        'data' => [
            [
                'banner_picture' => 'https://example.com/banner.jpg',
                'broadcaster_user_id' => 123456,
                'category' => [
                    'id' => 1,
                    'name' => 'Just Chatting',
                    'thumbnail' => 'https://example.com/category.jpg',
                ],
                'channel_description' => 'This is a test channel',
                'slug' => 'test_channel',
                'stream' => [
                    'is_live' => true,
                    'is_mature' => false,
                    'key' => 'stream_key',
                    'language' => 'en',
                    'start_time' => '2023-01-01T12:00:00Z',
                    'url' => 'https://example.com/stream',
                    'viewer_count' => 1000,
                ],
                'stream_title' => 'Test Stream',
            ],
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Call the method
    $channel = $resource->myChannel();

    // Assert response
    expect($channel)->toBeInstanceOf(KickChannelEntity::class)
        ->and($channel->banner_picture)->toBe('https://example.com/banner.jpg')
        ->and($channel->broadcaster_user_id)->toBe(123456)
        ->and($channel->category)->toBeInstanceOf(KickCategoryEntity::class)
        ->and($channel->category->id)->toBe(1)
        ->and($channel->category->name)->toBe('Just Chatting')
        ->and($channel->channel_description)->toBe('This is a test channel')
        ->and($channel->slug)->toBe('test_channel')
        ->and($channel->stream)->toBeInstanceOf(KickStreamEntity::class)
        ->and($channel->stream->is_live)->toBeTrue()
        ->and($channel->stream->viewer_count)->toBe(1000)
        ->and($channel->stream_title)->toBe('Test Stream');
});

test('can get channel by id', function () {
    // Mock response data
    $responseData = [
        'data' => [
            [
                'banner_picture' => 'https://example.com/banner.jpg',
                'broadcaster_user_id' => 123456,
                'category' => [
                    'id' => 1,
                    'name' => 'Just Chatting',
                    'thumbnail' => 'https://example.com/category.jpg',
                ],
                'channel_description' => 'This is a test channel',
                'slug' => 'test_channel',
                'stream' => [
                    'is_live' => true,
                    'is_mature' => false,
                    'key' => 'stream_key',
                    'language' => 'en',
                    'start_time' => '2023-01-01T12:00:00Z',
                    'url' => 'https://example.com/stream',
                    'viewer_count' => 1000,
                ],
                'stream_title' => 'Test Stream',
            ],
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Call the method
    $channel = $resource->getChannelById(123456);

    // Assert response
    expect($channel)->toBeInstanceOf(KickChannelEntity::class)
        ->and($channel->banner_picture)->toBe('https://example.com/banner.jpg')
        ->and($channel->broadcaster_user_id)->toBe(123456)
        ->and($channel->category)->toBeInstanceOf(KickCategoryEntity::class)
        ->and($channel->category->id)->toBe(1)
        ->and($channel->category->name)->toBe('Just Chatting')
        ->and($channel->channel_description)->toBe('This is a test channel')
        ->and($channel->slug)->toBe('test_channel')
        ->and($channel->stream)->toBeInstanceOf(KickStreamEntity::class)
        ->and($channel->stream->is_live)->toBeTrue()
        ->and($channel->stream->viewer_count)->toBe(1000)
        ->and($channel->stream_title)->toBe('Test Stream');
});

test('throws exception when channel not found', function () {
    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode(['data' => [], 'message' => 'No channels found'], JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);

    // Call the method
    $resource->getChannelById(999999);
});

test('throws exception on API error', function () {
    // Create mock handler with a server error
    $mockHandler = new MockHandler([
        new ClientException(
            'Server Error',
            new Request('GET', 'test'),
            new Response(HttpResponse::HTTP_INTERNAL_SERVER_ERROR, [], json_encode(['error' => 'Server error'], JSON_THROW_ON_ERROR))
        ),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);

    // Call the method
    $resource->getChannelById(123456);
});

test('throws exception when missing required scope', function () {
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
        ),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);

    // The exception message should contain information about missing scope
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');

    // Call the method
    $resource->getChannelById(123456);
});

test('can get multiple channels by id', function () {
    // Mock response data
    $responseData = [
        'data' => [
            [
                'banner_picture' => 'https://example.com/banner1.jpg',
                'broadcaster_user_id' => 123456,
                'category' => [
                    'id' => 1,
                    'name' => 'Just Chatting',
                    'thumbnail' => 'https://example.com/category1.jpg',
                ],
                'channel_description' => 'This is channel 1',
                'slug' => 'channel_1',
                'stream' => [
                    'is_live' => true,
                    'is_mature' => false,
                    'key' => 'stream_key_1',
                    'language' => 'en',
                    'start_time' => '2023-01-01T12:00:00Z',
                    'url' => 'https://example.com/stream1',
                    'viewer_count' => 1000,
                ],
                'stream_title' => 'Stream 1',
            ],
            [
                'banner_picture' => 'https://example.com/banner2.jpg',
                'broadcaster_user_id' => 789012,
                'category' => [
                    'id' => 2,
                    'name' => 'Gaming',
                    'thumbnail' => 'https://example.com/category2.jpg',
                ],
                'channel_description' => 'This is channel 2',
                'slug' => 'channel_2',
                'stream' => [
                    'is_live' => false,
                    'is_mature' => true,
                    'key' => 'stream_key_2',
                    'language' => 'es',
                    'start_time' => '2023-01-02T12:00:00Z',
                    'url' => 'https://example.com/stream2',
                    'viewer_count' => 500,
                ],
                'stream_title' => 'Stream 2',
            ],
        ],
        'message' => 'Success',
    ];

    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode($responseData, JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Call the method
    $channels = $resource->getChannelsById([123456, 789012]);

    // Assert response
    expect($channels)->toBeArray()
        ->and($channels)->toHaveCount(2)
        ->and($channels[0])->toBeInstanceOf(KickChannelEntity::class)
        ->and($channels[0]->broadcaster_user_id)->toBe(123456)
        ->and($channels[0]->slug)->toBe('channel_1')
        ->and($channels[1])->toBeInstanceOf(KickChannelEntity::class)
        ->and($channels[1]->broadcaster_user_id)->toBe(789012)
        ->and($channels[1]->slug)->toBe('channel_2');
});

test('throws exception when no channels found in multiple request', function () {
    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_OK, [], json_encode(['data' => [], 'message' => 'No channels found'], JSON_THROW_ON_ERROR)),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);

    // Call the method
    $resource->getChannelsById([999999, 888888]);
});

test('can update channel', function () {
    // Create mock handler
    $mockHandler = new MockHandler([
        new Response(HttpResponse::HTTP_NO_CONTENT, []),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $updateChannelDTO = new UpdateChannelDTO(
        categoryId: 123,
        streamTitle: 'Updated Stream Title'
    );

    // Call the method
    $result = $resource->updateChannel($updateChannelDTO);

    // Assert response
    expect($result)->toBeTrue();
});

test('throws exception when updating channel with missing scope', function () {
    // Create mock handler with 401 Unauthorized response
    $mockHandler = new MockHandler([
        new ClientException(
            'Unauthorized',
            new Request('PATCH', 'test'),
            new Response(
                HttpResponse::HTTP_UNAUTHORIZED,
                [],
                json_encode(['data' => [], 'message' => 'Unauthorized'], JSON_THROW_ON_ERROR)
            )
        ),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $updateChannelDTO = new UpdateChannelDTO(
        categoryId: 123,
        streamTitle: 'Updated Stream Title'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);
    $this->expectExceptionMessage('Access denied. You may be missing the required scope');

    // Call the method
    $resource->updateChannel($updateChannelDTO);
});

test('throws exception when channel not found during update', function () {
    // Create mock handler with 404 Not Found response
    $mockHandler = new MockHandler([
        new ClientException(
            'Not Found',
            new Request('PATCH', 'test'),
            new Response(
                HttpResponse::HTTP_NOT_FOUND,
                [],
                json_encode(['data' => [], 'message' => 'Channel not found'], JSON_THROW_ON_ERROR)
            )
        ),
    ]);

    // Create resource with mock client
    $resource = new KickStreamResource(
        client: new Client(['handler' => $mockHandler]),
        accessToken: 'test_access_token'
    );

    // Create DTO
    $updateChannelDTO = new UpdateChannelDTO(
        categoryId: 123,
        streamTitle: 'Updated Stream Title'
    );

    // Expect exception
    $this->expectException(KickStreamException::class);

    // Call the method
    $resource->updateChannel($updateChannelDTO);
});

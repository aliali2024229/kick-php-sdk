<?php

use DanielHe4rt\KickSDK\Chat\KickChatException;
use DanielHe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;

test('can create message send failed exception', function () {
    $exception = new \Exception('Test error message', 500);
    $chatException = KickChatException::messageSendFailed($exception);

    expect($chatException)->toBeInstanceOf(KickChatException::class)
        ->and($chatException->getMessage())->toContain('[Kick Chat Send Failed]')
        ->and($chatException->getMessage())->toContain('Test error message')
        ->and($chatException->getCode())->toBe(500);
});

test('can create channel not found exception', function () {
    $chatException = KickChatException::channelNotFound('123');

    expect($chatException)->toBeInstanceOf(KickChatException::class)
        ->and($chatException->getMessage())->toContain('[Kick Channel Not Found]')
        ->and($chatException->getMessage())->toContain("Channel with ID '123' was not found.");
});

test('can create missing scope exception', function () {
    $chatException = KickChatException::missingScope(KickOAuthScopesEnum::CHAT_WRITE);

    expect($chatException)->toBeInstanceOf(KickChatException::class)
        ->and($chatException->getMessage())->toContain('[Kick Unauthorized]')
        ->and($chatException->getMessage())->toContain('Access denied. You may be missing the required scope')
        ->and($chatException->getMessage())->toContain(KickOAuthScopesEnum::CHAT_WRITE->value)
        ->and($chatException->getCode())->toBe(401);
});

test('can create forbidden exception', function () {
    $chatException = KickChatException::forbidden('You do not have permission to send messages to this channel.');

    expect($chatException)->toBeInstanceOf(KickChatException::class)
        ->and($chatException->getMessage())->toContain('[Kick Forbidden]')
        ->and($chatException->getMessage())->toContain('You do not have permission to send messages to this channel.')
        ->and($chatException->getCode())->toBe(403);
});

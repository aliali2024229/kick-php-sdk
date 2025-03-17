<?php

use DanielHe4rt\KickSDK\Events\KickEventsException;
use DanielHe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;

test('can create retrieval failed exception', function () {
    $exception = new \Exception('Test error message', 500);
    $eventsException = KickEventsException::retrievalFailed($exception);

    expect($eventsException)->toBeInstanceOf(KickEventsException::class)
        ->and($eventsException->getMessage())->toContain('[Kick Events Retrieval Failed]')
        ->and($eventsException->getMessage())->toContain('Test error message')
        ->and($eventsException->getCode())->toBe(500);
});

test('can create creation failed exception', function () {
    $exception = new \Exception('Test error message', 500);
    $eventsException = KickEventsException::creationFailed($exception);

    expect($eventsException)->toBeInstanceOf(KickEventsException::class)
        ->and($eventsException->getMessage())->toContain('[Kick Events Creation Failed]')
        ->and($eventsException->getMessage())->toContain('Test error message')
        ->and($eventsException->getCode())->toBe(500);
});

test('can create deletion failed exception', function () {
    $exception = new \Exception('Test error message', 500);
    $eventsException = KickEventsException::deletionFailed($exception);

    expect($eventsException)->toBeInstanceOf(KickEventsException::class)
        ->and($eventsException->getMessage())->toContain('[Kick Events Deletion Failed]')
        ->and($eventsException->getMessage())->toContain('Test error message')
        ->and($eventsException->getCode())->toBe(500);
});

test('can create missing scope exception', function () {
    $eventsException = KickEventsException::missingScope(KickOAuthScopesEnum::EVENTS_READ);

    expect($eventsException)->toBeInstanceOf(KickEventsException::class)
        ->and($eventsException->getMessage())->toContain('[Kick Unauthorized]')
        ->and($eventsException->getMessage())->toContain('Access denied. You may be missing the required scope')
        ->and($eventsException->getMessage())->toContain(KickOAuthScopesEnum::EVENTS_READ->value)
        ->and($eventsException->getCode())->toBe(401);
});

test('can create forbidden exception', function () {
    $eventsException = KickEventsException::forbidden('You do not have permission to access event subscriptions.');

    expect($eventsException)->toBeInstanceOf(KickEventsException::class)
        ->and($eventsException->getMessage())->toContain('[Kick Forbidden]')
        ->and($eventsException->getMessage())->toContain('You do not have permission to access event subscriptions.')
        ->and($eventsException->getCode())->toBe(403);
});

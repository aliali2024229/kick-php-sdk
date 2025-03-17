<?php

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\Users\KickUserException;

test('can create user fetch failed exception', function () {
    $exception = KickUserException::userFetchFailed('Test error message', 500);

    expect($exception)->toBeInstanceOf(KickUserException::class)
        ->and($exception->getMessage())->toContain('[Kick User Fetch Failed]')
        ->and($exception->getMessage())->toContain('Test error message')
        ->and($exception->getCode())->toBe(500);
});

test('can create user not found exception', function () {
    $exception = KickUserException::userNotFound('123');

    expect($exception)->toBeInstanceOf(KickUserException::class)
        ->and($exception->getMessage())->toContain('[Kick User Not Found]')
        ->and($exception->getMessage())->toContain("User with ID '123' was not found.");
});

test('can create users not found exception', function () {
    $exception = KickUserException::usersNotFound();

    expect($exception)->toBeInstanceOf(KickUserException::class)
        ->and($exception->getMessage())->toContain('[Kick Users Not Found]')
        ->and($exception->getMessage())->toContain("No users were found.");
});

test('can create missing scope exception', function () {
    $exception = KickUserException::missingScope(KickOAuthScopesEnum::USER_READ);

    expect($exception)->toBeInstanceOf(KickUserException::class)
        ->and($exception->getMessage())->toContain('[Kick Unauthorized]')
        ->and($exception->getMessage())->toContain('Access denied. You may be missing the required scope')
        ->and($exception->getMessage())->toContain(KickOAuthScopesEnum::USER_READ->value)
        ->and($exception->getCode())->toBe(401);
}); 
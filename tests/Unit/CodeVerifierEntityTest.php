<?php

use Danielhe4rt\KickSDK\OAuth\Entities\CodeVerifierEntity;

test('can create CodeVerifierEntity with constructor', function () {
    $entity = new CodeVerifierEntity(
        code: 'test_code',
        codeVerifier: 'test_verifier'
    );

    expect($entity->getCode())->toBe('test_code')
        ->and($entity->getVerifier())->toBe('test_verifier');
});

test('can create CodeVerifierEntity with make method', function () {
    $entity = CodeVerifierEntity::make();

    expect($entity)->toBeInstanceOf(CodeVerifierEntity::class)
        ->and($entity->getCode())->toBeString()
        ->and(strlen($entity->getCode()) > 0)->toBeTrue()
        ->and($entity->getVerifier())->toBeString()
        ->and(strlen($entity->getVerifier()) > 0)->toBeTrue();
});

test('code challenge is correctly generated from verifier', function () {
    // Create a reflection of the private method
    $reflectionClass = new ReflectionClass(CodeVerifierEntity::class);
    $generateCodeChallengeMethod = $reflectionClass->getMethod('generateCodeChallenge');
    $generateCodeChallengeMethod->setAccessible(true);

    // Test with a known verifier
    $codeVerifier = 'test_verifier';
    $expectedChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    
    $actualChallenge = $generateCodeChallengeMethod->invoke(null, $codeVerifier);
    
    expect($actualChallenge)->toBe($expectedChallenge);
});

test('code verifier has correct length', function () {
    // Create a reflection of the private method
    $reflectionClass = new ReflectionClass(CodeVerifierEntity::class);
    $generateCodeVerifierMethod = $reflectionClass->getMethod('generateCodeVerifier');
    $generateCodeVerifierMethod->setAccessible(true);
    
    // Test with default length
    $verifier = $generateCodeVerifierMethod->invoke(null);
    expect(strlen($verifier))->toBe(128);
    
    // Test with custom length
    $verifier = $generateCodeVerifierMethod->invoke(null, 64);
    expect(strlen($verifier))->toBe(64);
}); 
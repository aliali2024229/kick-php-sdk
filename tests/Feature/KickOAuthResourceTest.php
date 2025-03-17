<?php


use Danielhe4rt\KickSDK\OAuth\DTOs\AuthenticateDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RedirectUrlDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RefreshTokenDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RevokeTokenDTO;
use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use Danielhe4rt\KickSDK\OAuth\Entities\KickIntrospectTokenEntity;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\OAuth\Enums\KickTokenHintTypeEnum;
use Danielhe4rt\KickSDK\OAuth\KickOAuthResource;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

test('can build the redirect uri', function () {

    $resource = new KickOAuthResource(
        client: new Client(),
        clientId: 'client_id',
        clientSecret: 'client_secret'
    );

    $redirectDTO = RedirectUrlDTO::make(
        clientId: 'client_id',
        redirectUri: 'https://example.com/callback',
        responseType: 'code',
        scopes: [KickOAuthScopesEnum::CHANNEL_READ, KickOAuthScopesEnum::CHAT_WRITE],
        state: 'state_value',
    );

    $response = $resource->redirectUrl($redirectDTO);

    $expectedUrl = "https://id.kick.com/oauth/authorize?client_id=client_id&redirect_uri=https%3A%2F%2Fexample.com%2Fcallback&response_type=code&state=state_value&code_challenge=" . $redirectDTO->codeChallenge->getCode() . "&code_challenge_method=S256&scope=channel%3Aread+chat%3Awrite";
    expect($response)->toBe($expectedUrl);
});


/**
 * Test if the authenticate method works as expected.
 *
 * @see https://docs.kick.com/getting-started/generating-tokens-oauth2-flow
 */
test('can authenticate', function () {


    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [], json_encode([
            'access_token' => 'access_token_value',
            'expires_in' => 3600,
            'refresh_token' => 'refresh_token_value',
            'scope' => 'channel:read chat:write',
            'token_type' => 'Bearer',
        ], JSON_THROW_ON_ERROR)),
    ]);

    $resource = new KickOAuthResource(
        client: new Client(['handler' => $mockHandler]),
        clientId: 'client_id',
        clientSecret: 'client_secret'
    );

    $authenticateDTO = AuthenticateDTO::make(
        code: 'authorization_code_value',
        codeVerifier: 'definitely_a_code_verifier_value',
    );

    $response = $resource->authenticate($authenticateDTO);

    expect($response)->toBeInstanceOf(KickAccessTokenEntity::class);
});

test('can refresh token', function () {

    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [], json_encode([
            'access_token' => 'new_access_token_value',
            'expires_in' => 3600,
            'refresh_token' => 'new_refresh_token_value',
            'scope' => 'channel:read chat:write',
            'token_type' => 'Bearer',
        ], JSON_THROW_ON_ERROR)),
    ]);

    $resource = new KickOAuthResource(
        client: new Client(['handler' => $mockHandler]),
        clientId: 'client_id',
        clientSecret: 'client_secret'
    );

    $refreshTokenDTO = RefreshTokenDTO::make('refresh_token_value');
    $response = $resource->refreshToken($refreshTokenDTO);

    expect($response)->toBeInstanceOf(KickAccessTokenEntity::class)
        ->and($response->access_token)->toBe('new_access_token_value');
});

test('can revoke token', function () {
    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [])
    ]);

    $resource = new KickOAuthResource(
        client: new Client(['handler' => $mockHandler]),
        clientId: 'client_id',
        clientSecret: 'client_secret'
    );

    $refreshTokenDTO = RevokeTokenDTO::make('refresh_token_value', tokenHintType: KickTokenHintTypeEnum::ACCESS_TOKEN);
    $response = $resource->revokeToken($refreshTokenDTO);

    expect($response)->toBeTrue();
});


test('can introspect token', function () {
    $mockHandler = new MockHandler([
        new Response(\Symfony\Component\HttpFoundation\Response::HTTP_OK, [], json_encode([
            'data' => [
                'active' => true,
                'client_id' => 'text',
                'exp' => 1,
                'scope' => 'text',
                'token_type' => 'text',
            ],
            'message' => 'text',
        ], JSON_THROW_ON_ERROR)),
    ]);

    $resource = new KickOAuthResource(
        client: new Client(['handler' => $mockHandler]),
        clientId: 'client_id',
        clientSecret: 'client_secret'
    );

    $response = $resource->introspectToken('access_token_value');

    expect($response)->toBeInstanceOf(KickIntrospectTokenEntity::class)
        ->and($response->active)->toBeTrue()
        ->and($response->clientId)->toBe('text')
        ->and($response->exp)->toBe(1)
        ->and($response->scope)->toBe('text')
        ->and($response->tokenType)->toBe('text')
        ->and($response->message)->toBe('text');
});
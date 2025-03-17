<?php

namespace Danielhe4rt\KickSDK\OAuth;


use Danielhe4rt\KickSDK\OAuth\DTOs\AuthenticateDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RedirectUrlDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RefreshTokenDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RevokeTokenDTO;
use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use Danielhe4rt\KickSDK\OAuth\Entities\KickIntrospectTokenEntity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

readonly class KickOAuthResource
{
    public function __construct(
        public Client $client,
        public string $clientId,
        public string $clientSecret,
    )
    {

    }

    public function authenticate(AuthenticateDTO $authenticateDTO): KickAccessTokenEntity
    {
        try {
            $response = $this->client->post('https://id.kick.com/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $authenticateDTO->code,
                    'redirect_uri' => $authenticateDTO->redirectUri,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code_verifier' => $authenticateDTO->codeVerifier,
                ]
            ]);
        } catch (GuzzleException $e) {
            throw KickOAuthException::authenticationFailed($e->getMessage(), $e->getCode());
        }

        return KickAccessTokenEntity::fromArray(
            json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    public function refreshToken(RefreshTokenDTO $refreshTokenDTO): KickAccessTokenEntity
    {
        try {
            $response = $this->client->post('https://id.kick.com/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshTokenDTO->refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]);
        } catch (GuzzleException $e) {
            throw KickOAuthException::refreshTokenFailed($e->getMessage(), $e->getCode());
        }

        return KickAccessTokenEntity::fromArray(json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function revokeToken(RevokeTokenDTO $revokeTokenDTO): bool
    {
        try {
            $response = $this->client->post(
                sprintf('https://id.kick.com/oauth/revoke?%s', http_build_query($revokeTokenDTO->toQueryParams())),
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]
                ]
            );
        } catch (GuzzleException $e) {
            throw KickOAuthException::revokeTokenFailed($e->getMessage(), $e->getCode());
        }

        return true;
    }

    public function introspectToken(string $accessToken): KickIntrospectTokenEntity
    {
        try {
            $response = $this->client->post('https://api.kick.com/public/v1/token/introspect', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            throw KickOAuthException::introspectTokenFailed($e->getMessage(), $e->getCode());
        }

        return KickIntrospectTokenEntity::fromArray(
            json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    public function redirectUrl(RedirectUrlDTO $redirectUrlDTO): string
    {
        return sprintf('https://id.kick.com/oauth/authorize?%s', http_build_query($redirectUrlDTO->jsonSerialize()));
    }

}
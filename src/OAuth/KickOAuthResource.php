<?php

namespace Danielhe4rt\KickSDK\OAuth;


use Danielhe4rt\KickSDK\OAuth\DTOs\AuthenticateDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\AuthorizeDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RedirectUrlDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RefreshTokenDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RevokeTokenDTO;
use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use Danielhe4rt\KickSDK\OAuth\Entities\KickIntrospectTokenEntity;
use GuzzleHttp\Client;
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

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            // Optionally, log the error or handle it as needed
            throw KickOAuthException::authenticationFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        return KickAccessTokenEntity::fromArray(json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function refreshToken(RefreshTokenDTO $refreshTokenDTO): KickAccessTokenEntity
    {
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

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw KickOAuthException::refreshTokenFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        return KickAccessTokenEntity::fromArray(json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR));
    }

    public function revokeToken(RevokeTokenDTO $revokeTokenDTO): bool
    {
        $response = $this->client->post(
            sprintf('https://id.kick.com/oauth/revoke?%s', http_build_query($revokeTokenDTO->toQueryParams())),
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw KickOAuthException::revokeTokenFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        return true;
    }

    public function introspectToken(string $accessToken): KickIntrospectTokenEntity
    {
        $response = $this->client->post('https://api.kick.com/public/v1/token/introspect', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw KickOAuthException::introspectTokenFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
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
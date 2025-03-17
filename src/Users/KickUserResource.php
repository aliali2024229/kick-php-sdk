<?php

namespace Danielhe4rt\KickSDK\Users;

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\Users\Entities\KickUserEntity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

readonly class KickUserResource
{
    const GET_USERS_URI = 'https://api.kick.com/public/v1/users';

    public function __construct(
        public Client $client,
        public string $accessToken,
    )
    {
    }

    /**
     * Get the authenticated user's information
     */
    public function me(): KickUserEntity
    {
        try {
            $response = $this->client->get(self::GET_USERS_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickUserException::missingScope(KickOAuthScopesEnum::USER_READ),
                Response::HTTP_NOT_FOUND => throw KickUserException::userNotFound('current user'),
                default => throw KickUserException::userFetchFailed($e->getMessage(), $e->getCode()),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        return KickUserEntity::fromArray($responsePayload['data'][0]);
    }

    /**
     * Get a user by their ID
     */
    public function fetchUserById(string $userId): KickUserEntity
    {
        try {
            $response = $this->client->get(self::GET_USERS_URI, [
                'query' => [
                    'id' => $userId,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickUserException::missingScope(KickOAuthScopesEnum::USER_READ),
                Response::HTTP_NOT_FOUND => throw KickUserException::userNotFound($userId),
                default => throw KickUserException::userFetchFailed($e->getMessage(), $e->getCode()),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($responsePayload['data']) === 0) {
            throw KickUserException::userNotFound($userId);
        }

        return KickUserEntity::fromArray($responsePayload['data'][0]);
    }

    /**
     * Get users by their IDs
     *
     * @param array $userIds
     * @return KickUserEntity[]
     *
     */
    public function fetchUsersById(array $userIds): array
    {
        try {
            $response = $this->client->get(self::GET_USERS_URI, [
                'query' => [
                    'id' => $userIds,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickUserException::missingScope(KickOAuthScopesEnum::USER_READ),
                Response::HTTP_NOT_FOUND => throw KickUserException::usersNotFound(),
                default => throw KickUserException::userFetchFailed($e->getMessage(), $e->getCode()),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($responsePayload['data']) === 0) {
            throw KickUserException::usersNotFound();
        }

        return array_map(fn(array $user) => KickUserEntity::fromArray($user), $responsePayload['data']);
    }

} 
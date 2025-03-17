<?php

namespace Danielhe4rt\KickSDK\Users;

use Danielhe4rt\KickSDK\Users\Entities\KickUserEntity;
use GuzzleHttp\Client;
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
        $response = $this->client->get(self::GET_USERS_URI, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw UserException::userFetchFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        return KickUserEntity::fromArray($responsePayload['data'][0]);
    }

    /**
     * Get a user by their ID
     */
    public function fetchUserById(string $userId): KickUserEntity
    {
        $response = $this->client->get(self::GET_USERS_URI, [
            'query' => [
                'id' => $userId,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw UserException::userFetchFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($response['data']) === 0) {
            throw UserException::userNotFound($userId);
        }

        return KickUserEntity::fromArray($response['data'][0]);
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
        $response = $this->client->get(self::GET_USERS_URI, [
            'query' => [
                'id' => $userIds,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw UserException::userFetchFailed(
                $response->getBody()->getContents(),
                $response->getStatusCode()
            );
        }

        $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (count($response['data']) === 0) {
            throw UserException::usersNotFound();
        }

        return array_map(fn(array $user) => KickUserEntity::fromArray($user), $response['data']);
    }

} 
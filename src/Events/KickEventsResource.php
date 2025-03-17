<?php

namespace Danielhe4rt\KickSDK\Events;

use Danielhe4rt\KickSDK\Events\DTOs\CreateEventSubscriptionDTO;
use Danielhe4rt\KickSDK\Events\Entities\KickEventSubscriptionEntity;
use Danielhe4rt\KickSDK\Events\Entities\KickEventSubscriptionResponseEntity;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

readonly class KickEventsResource
{
    public const EVENTS_URI = 'https://api.kick.com/public/v1/events/subscriptions';

    public function __construct(
        public Client $client,
        public string $accessToken,
    )
    {
    }

    /**
     * Get all event subscriptions
     *
     * @return KickEventSubscriptionEntity[]
     * @throws KickEventsException
     */
    public function getSubscriptions(): array
    {
        try {
            $response = $this->client->get(self::EVENTS_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickEventsException::missingScope(KickOAuthScopesEnum::EVENTS_READ),
                Response::HTTP_FORBIDDEN => throw KickEventsException::forbidden('You do not have permission to access event subscriptions.'),
                default => throw KickEventsException::retrievalFailed($e),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $subscriptions = [];
        foreach ($responsePayload['data'] as $subscription) {
            $subscriptions[] = KickEventSubscriptionEntity::fromArray($subscription);
        }

        return $subscriptions;
    }

    /**
     * Create new event subscriptions
     *
     * @param CreateEventSubscriptionDTO $subscriptionDTO
     * @return KickEventSubscriptionResponseEntity[] Array of subscription results
     * @throws KickEventsException
     */
    public function subscribe(CreateEventSubscriptionDTO $subscriptionDTO): array
    {
        try {
            $response = $this->client->post(self::EVENTS_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $subscriptionDTO->jsonSerialize(),
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickEventsException::missingScope(KickOAuthScopesEnum::EVENTS_WRITE),
                Response::HTTP_FORBIDDEN => throw KickEventsException::forbidden('You do not have permission to create event subscriptions.'),
                default => throw KickEventsException::creationFailed($e),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return array_map(static fn($subscription) => KickEventSubscriptionResponseEntity::fromArray($subscription), $responsePayload['data']);
    }

    /**
     * Delete event subscriptions
     *
     * @param string $subscriptionId subscription ID to delete
     * @return bool Whether the deletion was successful
     * @throws KickEventsException
     */
    public function unsubscribe(string $subscriptionId): bool
    {
        try {
            $this->client->delete(self::EVENTS_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                ],
                'query' => [
                    'id' => $subscriptionId,
                ],
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickEventsException::missingScope(KickOAuthScopesEnum::EVENTS_WRITE),
                Response::HTTP_FORBIDDEN => throw KickEventsException::forbidden('You do not have permission to delete event subscriptions.'),
                default => throw KickEventsException::deletionFailed($e),
            };
        }

        return true;
    }
} 
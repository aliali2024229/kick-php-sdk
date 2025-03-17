<?php

namespace Danielhe4rt\KickSDK\PublicKey;

use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\PublicKey\Entities\KickPublicKeyEntity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

readonly class KickPublicKeyResource
{
    public const PUBLIC_KEY_URI = 'https://api.kick.com/public/v1/public-key';

    public function __construct(
        public Client $client,
    )
    {
    }

    /**
     * Get the public key used for verifying signatures
     * 
     * @return KickPublicKeyEntity
     * @throws KickPublicKeyException
     */
    public function getPublicKey(): KickPublicKeyEntity
    {
        try {
            $response = $this->client->get(self::PUBLIC_KEY_URI);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickPublicKeyException::missingScope(KickOAuthScopesEnum::STREAMKEY_READ),
                default => throw KickPublicKeyException::retrievalFailed($e)
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        
        return KickPublicKeyEntity::fromArray($responsePayload['data']);
    }
} 
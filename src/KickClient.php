<?php

namespace Danielhe4rt\KickSDK;

use Danielhe4rt\KickSDK\Chat\KickChatResource;
use Danielhe4rt\KickSDK\Events\KickEventsResource;
use Danielhe4rt\KickSDK\OAuth\KickOAuthResource;
use Danielhe4rt\KickSDK\PublicKey\KickPublicKeyResource;
use Danielhe4rt\KickSDK\Streams\KickStreamResource;
use Danielhe4rt\KickSDK\Users\KickUserResource;
use GuzzleHttp\Client;

final readonly class KickClient
{
    public Client $client;

    public function __construct(
        public string $clientId,
        public string $clientSecret,
    )
    {
        $this->client = new Client([]);
    }

    public function oauth(): KickOAuthResource
    {
        return new KickOAuthResource(
            $this->client,
            $this->clientId,
            $this->clientSecret,
        );
    }

    public function users(string $accessToken): KickUserResource
    {
        return new KickUserResource(
            $this->client,
            $accessToken
        );
    }

    public function streams(string $accessToken): KickStreamResource
    {
        return new KickStreamResource(
            $this->client,
            $accessToken
        );
    }
    
    public function chat(string $accessToken): KickChatResource
    {
        return new KickChatResource(
            $this->client,
            $accessToken
        );
    }
    
    public function publicKey(): KickPublicKeyResource
    {
        return new KickPublicKeyResource(
            $this->client
        );
    }
    
    public function events(string $accessToken): KickEventsResource
    {
        return new KickEventsResource(
            $this->client,
            $accessToken
        );
    }
}
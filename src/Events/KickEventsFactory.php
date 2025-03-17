<?php

namespace Danielhe4rt\KickSDK\Events;

use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

class KickEventsFactory
{
    /**
     * Create a new KickEventsResource instance
     * 
     * @param KickAccessTokenEntity $accessToken
     * @param Client|null $client
     * @return KickEventsResource
     */
    public static function make(KickAccessTokenEntity $accessToken, ?Client $client = null): KickEventsResource
    {
        return new KickEventsResource(
            client: $client ?? new Client(),
            accessToken: $accessToken->accessToken,
        );
    }
} 
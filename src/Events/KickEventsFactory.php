<?php

namespace Danielhe4rt\KickSDK\Events;

use DanielHe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

class KickEventsFactory
{
    /**
     * Create a new KickEventsResource instance
     */
    public static function make(KickAccessTokenEntity $accessToken, ?Client $client = null): KickEventsResource
    {
        return new KickEventsResource(
            client: $client ?? new Client,
            accessToken: $accessToken->accessToken,
        );
    }
}

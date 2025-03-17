<?php

namespace DanielHe4rt\KickSDK\Chat;

use DanielHe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

class KickChatFactory
{
    /**
     * Create a new KickChatResource instance
     */
    public static function make(KickAccessTokenEntity $accessToken, ?Client $client = null): KickChatResource
    {
        return new KickChatResource(
            client: $client ?? new Client,
            accessToken: $accessToken->accessToken,
        );
    }
}

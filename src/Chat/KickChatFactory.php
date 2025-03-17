<?php

namespace Danielhe4rt\KickSDK\Chat;

use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

class KickChatFactory
{
    /**
     * Create a new KickChatResource instance
     * 
     * @param KickAccessTokenEntity $accessToken
     * @param Client|null $client
     * @return KickChatResource
     */
    public static function make(KickAccessTokenEntity $accessToken, ?Client $client = null): KickChatResource
    {
        return new KickChatResource(
            client: $client ?? new Client(),
            accessToken: $accessToken->accessToken,
        );
    }
} 
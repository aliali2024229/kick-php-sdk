<?php

namespace Danielhe4rt\KickSDK\Streams;

use Danielhe4rt\KickSDK\OAuth\Entities\KickAccessTokenEntity;
use GuzzleHttp\Client;

class KickStreamFactory
{
    public static function make(KickAccessTokenEntity $accessToken, ?Client $client = null): KickStreamResource
    {
        return new KickStreamResource(
            client: $client ?? new Client(),
            accessToken: $accessToken->accessToken,
        );
    }
} 
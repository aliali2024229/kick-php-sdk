<?php

namespace Danielhe4rt\KickSDK\PublicKey;

use GuzzleHttp\Client;

class KickPublicKeyFactory
{
    /**
     * Create a new KickPublicKeyResource instance
     * 
     * @param Client|null $client
     * @return KickPublicKeyResource
     */
    public static function make(?Client $client = null): KickPublicKeyResource
    {
        return new KickPublicKeyResource(
            client: $client ?? new Client(),
        );
    }
} 
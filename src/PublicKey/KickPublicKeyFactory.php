<?php

namespace DanielHe4rt\KickSDK\PublicKey;

use GuzzleHttp\Client;

class KickPublicKeyFactory
{
    /**
     * Create a new KickPublicKeyResource instance
     */
    public static function make(?Client $client = null): KickPublicKeyResource
    {
        return new KickPublicKeyResource(
            client: $client ?? new Client,
        );
    }
}

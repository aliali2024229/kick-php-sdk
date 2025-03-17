<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks;

use DanielHe4rt\KickSDK\PublicKey\KickPublicKeyResource;

class KickWebhookVerifier
{
    /**
     * @param  string  $publicKey  The public key to use for verification
     */
    public function __construct(
        private readonly string $publicKey,
    ) {}

    /**
     * Create a new verifier using the public key from the API
     */
    public static function fromResource(KickPublicKeyResource $publicKeyResource): self
    {
        $publicKeyEntity = $publicKeyResource->getPublicKey();

        return new self($publicKeyEntity->publicKey);
    }

    /**
     * Verify a webhook signature
     *
     * @param  string  $signature  The signature from the Kick-Signature header
     * @param  string  $payload  The raw request body
     * @return bool Whether the signature is valid
     */
    public function verify(string $signature, string $payload): bool
    {
        $publicKeyPem = "-----BEGIN PUBLIC KEY-----\n".
            chunk_split($this->publicKey, 64, "\n").
            '-----END PUBLIC KEY-----';

        $publicKeyResource = openssl_pkey_get_public($publicKeyPem);
        if ($publicKeyResource === false) {
            return false;
        }

        // Decode the base64 signature
        $decodedSignature = base64_decode($signature);
        if ($decodedSignature === false) {
            return false;
        }

        // Verify the signature
        return openssl_verify($payload, $decodedSignature, $publicKeyResource, OPENSSL_ALGO_SHA256) === 1;
    }
}

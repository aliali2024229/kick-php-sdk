<?php

namespace Danielhe4rt\KickSDK\OAuth\DTOs;

use Danielhe4rt\KickSDK\OAuth\Entities\CodeVerifierEntity;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\OAuth\KickOAuthException;
use JsonSerializable;

readonly class RedirectUrlDTO implements JsonSerializable
{

    /**
     * @param string $clientId
     * @param string $redirectUri
     * @param string $responseType
     * @param KickOAuthScopesEnum[] $scopes
     * @param string $state
     * @param CodeVerifierEntity $codeChallenge
     */
    public function __construct(
        public string             $clientId,
        public string             $redirectUri,
        public string             $responseType,
        public array              $scopes,
        public string             $state,
        public CodeVerifierEntity $codeChallenge,
    )
    {

    }


    public static function make(
        string $clientId,
        string $redirectUri,
        string $responseType,
        array  $scopes,
        string $state,
    ): self
    {
        // Validate the scopes

        foreach ($scopes as $scope) {
            if (!$scope instanceof KickOAuthScopesEnum) {
                throw KickOAuthException::invalidScope($scope);
            }
        }

        return new self(
            clientId: $clientId,
            redirectUri: $redirectUri,
            responseType: $responseType,
            scopes: $scopes,
            state: $state,
            codeChallenge: CodeVerifierEntity::make(),
        );
    }


    public function jsonSerialize(): array
    {
        return [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => $this->responseType,
            'state' => $this->state,
            'code_challenge' => $this->codeChallenge->getCode(),
            'code_challenge_method' => 'S256',
            'scope' => implode(' ', array_map(static fn(KickOAuthScopesEnum $scope) => $scope->value, $this->scopes)),
        ];
    }
}
<?php

namespace DanielHe4rt\KickSDK\OAuth\DTOs;

use DanielHe4rt\KickSDK\OAuth\Enums\KickTokenHintTypeEnum;

readonly class RevokeTokenDTO
{
    public function __construct(
        public string $token,
        public KickTokenHintTypeEnum $tokenHintType,
    ) {}

    public static function make(string $token, KickTokenHintTypeEnum $tokenHintType): self
    {
        return new self(
            token: $token,
            tokenHintType: $tokenHintType
        );
    }

    public function toQueryParams(): array
    {
        $params = [
            'token' => $this->token,
        ];

        if ($this->tokenHintType !== null) {
            $params['token_hint_type'] = $this->tokenHintType;
        }

        return $params;
    }
}

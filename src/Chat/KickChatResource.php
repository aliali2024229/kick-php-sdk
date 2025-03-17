<?php

namespace Danielhe4rt\KickSDK\Chat;

use Danielhe4rt\KickSDK\Chat\DTOs\SendChatMessageDTO;
use Danielhe4rt\KickSDK\Chat\Entities\KickChatMessageEntity;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

readonly class KickChatResource
{
    public const CHAT_URI = 'https://api.kick.com/public/v1/chat';

    public function __construct(
        public Client $client,
        public string $accessToken,
    )
    {
    }

    /**
     * Send a chat message to a channel
     * 
     * @param SendChatMessageDTO $messageDTO
     * @return KickChatMessageEntity
     * @throws KickChatException
     */
    public function sendMessage(SendChatMessageDTO $messageDTO): KickChatMessageEntity
    {
        try {
            $response = $this->client->post(self::CHAT_URI, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $messageDTO->jsonSerialize(),
            ]);
        } catch (GuzzleException $e) {
            match ($e->getCode()) {
                Response::HTTP_UNAUTHORIZED => throw KickChatException::missingScope(KickOAuthScopesEnum::CHAT_WRITE),
                Response::HTTP_FORBIDDEN => throw KickChatException::forbidden('You do not have permission to send messages to this channel.'),
                Response::HTTP_NOT_FOUND => throw KickChatException::channelNotFound($messageDTO->broadcaster_user_id ?? 'bot channel'),
                default => throw KickChatException::messageSendFailed($e),
            };
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        
        return KickChatMessageEntity::fromArray($responsePayload['data']);
    }
} 
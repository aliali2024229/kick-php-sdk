<?php

require_once __DIR__.'/../vendor/autoload.php';

use DanielHe4rt\KickSDK\Events\Webhooks\Enums\KickWebhookEventTypeEnum;
use DanielHe4rt\KickSDK\Events\Webhooks\KickWebhookVerifier;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChannelFollowedPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\ChatMessageSentPayload;
use DanielHe4rt\KickSDK\Events\Webhooks\Payloads\LivestreamStatusUpdatedPayload;
use DanielHe4rt\KickSDK\KickClient;
use DanielHe4rt\KickSDK\Webhooks\KickWebhookHandler;

// Create a Kick client and get the public key resource
$client = new KickClient;
$publicKeyResource = $client->publicKey();

// Create a webhook handler with signature verification
$handler = new KickWebhookHandler;
$handler->setVerifier(KickWebhookVerifier::fromResource($publicKeyResource));

// Add listeners for different event types
$handler->on(KickWebhookEventTypeEnum::ChatMessageSent, function (ChatMessageSentPayload $payload) {
    echo "New chat message from {$payload->sender->username}: {$payload->content}\n";
});

$handler->on(KickWebhookEventTypeEnum::ChannelFollowed, function (ChannelFollowedPayload $payload) {
    echo "{$payload->follower->username} followed {$payload->broadcaster->username}\n";
});

$handler->on(KickWebhookEventTypeEnum::LivestreamStatusUpdated, function (LivestreamStatusUpdatedPayload $payload) {
    if ($payload->isLive) {
        echo "{$payload->broadcaster->username} started streaming: {$payload->title}\n";
    } else {
        echo "{$payload->broadcaster->username} ended their stream\n";
    }
});

// Get the raw request body and headers
$body = file_get_contents('php://input');
$headers = getallheaders();

// Handle the webhook
$success = $handler->handle($body, $headers);
if (! $success) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid webhook payload']);
    exit;
}

// Return a success response
http_response_code(200);
echo json_encode(['success' => true]);

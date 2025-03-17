<?php

require_once __DIR__ . '/vendor/autoload.php';

use Danielhe4rt\KickSDK\Chat\DTOs\SendChatMessageDTO;
use Danielhe4rt\KickSDK\Chat\KickMessageTypeEnum;
use Danielhe4rt\KickSDK\KickClient;
use Danielhe4rt\KickSDK\OAuth\DTOs\AuthenticateDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RedirectUrlDTO;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;
use Danielhe4rt\KickSDK\Streams\DTOs\UpdateChannelDTO;

$clientId = '01JPFTZ08SCGX0YJP91YRVCEEQ';
$clientSecret = 'ad9f9b095f048316ece5e25ec6d1ac22f579c297a2b8f9d1d8193c4f710c28f7';

$kickClient = new KickClient(
    clientId: $clientId,
    clientSecret: $clientSecret,
);

$redirectUrlDTO = RedirectUrlDTO::make(
    clientId: $clientId,
    redirectUri: 'http://localhost:8000/oauth/kick',
    responseType: 'code',
    scopes: [
        KickOAuthScopesEnum::USER_READ,
        KickOAuthScopesEnum::EVENTS_SUBSCRIBE,
        KickOAuthScopesEnum::CHANNEL_READ,
        KickOAuthScopesEnum::CHANNEL_WRITE,
        KickOAuthScopesEnum::CHAT_WRITE,
    ],
    state: md5(time()),
);

echo $redirectUrlDTO->codeChallenge->getVerifier() . PHP_EOL;

$redirectUrl = $kickClient->oauth()->redirectUrl($redirectUrlDTO);

echo $redirectUrl . PHP_EOL;


echo "Paste the code you received in the redirect URL: ";
$code = trim(fgets(STDIN));

$authDTO = AuthenticateDTO::make(
    code: $code,
    codeVerifier: $redirectUrlDTO->codeChallenge->getVerifier(),
    redirectUrl: 'http://localhost:8000/oauth/kick',
);

$authToken = $kickClient->oauth()->authenticate($authDTO);

echo "Access Token: " . $authToken->accessToken . PHP_EOL;
echo "Refresh Token: " . $authToken->refreshToken . PHP_EOL;

$usersClient = $kickClient->users($authToken->accessToken);

$authenticatedUser = $usersClient->me();

echo "Authenticated User ID: " . $authenticatedUser->userId . PHP_EOL;
echo "Authenticated User Username: " . $authenticatedUser->username . PHP_EOL;
echo "Authenticated User Profile Picture: " . $authenticatedUser->profile_picture . PHP_EOL;
echo "Authenticated User Email: " . ($authenticatedUser->email ?? 'Not provided. See scopes') . PHP_EOL;

$findById = $usersClient->fetchUserById($authenticatedUser->userId);

echo "Find by ID User ID: " . $findById->userId . PHP_EOL;
echo "Find by ID User Username: " . $findById->username . PHP_EOL;
echo "Find by ID User Profile Picture: " . $findById->profile_picture . PHP_EOL;
echo "Find by ID User Email: " . ($findById->email ?? 'Not provided. See scopes') . PHP_EOL;

$fetchByIds = $usersClient->fetchUsersById([$authenticatedUser->userId]);

echo "Fetch by Id's: count " . count($fetchByIds) . PHP_EOL;

foreach ($fetchByIds as $user) {
    echo "Fetch by Id's User ID: " . $user->userId . PHP_EOL;
    echo "Fetch by Id's User Username: " . $user->username . PHP_EOL;
    echo "Fetch by Id's User Profile Picture: " . $user->profile_picture . PHP_EOL;
    echo "Fetch by Id's User Email: " . ($user->email ?? 'Not provided. See scopes') . PHP_EOL;
}

$streamClient = $kickClient->streams($authToken->accessToken);

$channel = $streamClient->getChannelById($authenticatedUser->userId);

echo "Channel ID: " . $channel->broadcaster_user_id . PHP_EOL;
echo "Channel Slug: " . $channel->slug . PHP_EOL;
echo "Channel Description: " . $channel->channel_description . PHP_EOL;
echo "Channel Banner Picture: " . $channel->banner_picture . PHP_EOL;
echo "Channel Category ID: " . $channel->category->id . PHP_EOL;
echo "Channel Category Name: " . $channel->category->name . PHP_EOL;
echo "Channel Category Thumbnail: " . $channel->category->thumbnail . PHP_EOL;
echo "Channel Stream Title: " . $channel->stream_title . PHP_EOL;
echo "Channel Stream Key: " . $channel->stream?->key . PHP_EOL;
echo "Channel Stream Language: " . $channel->stream?->language . PHP_EOL;
echo "Channel Stream URL: " . $channel->stream?->url . PHP_EOL;
echo "Channel Stream Viewer Count: " . $channel->stream?->viewer_count . PHP_EOL;
echo "Channel Stream Start Time: " . $channel->stream?->start_time . PHP_EOL;
echo "Channel Stream Is Live: " . ($channel->stream?->is_live ? 'Yes' : 'No') . PHP_EOL;
echo "Channel Stream Is Mature: " . ($channel->stream?->is_mature ? 'Yes' : 'No') . PHP_EOL;
echo "Channel Stream Tags: " . ($channel->stream?->tags ?? 'Not provided') . PHP_EOL;
echo "-------------------------" . PHP_EOL;

$updateChannelDTO = new UpdateChannelDTO(
    streamTitle: '[EN/BR] Coding a PHP SDK for Kick',
);

$updatedChannel = $streamClient->updateChannel($updateChannelDTO);

if ($updatedChannel) {
    echo "Channel updated successfully!" . PHP_EOL;
} else {
    echo "Failed to update channel." . PHP_EOL;
}

echo '-------------------------' . PHP_EOL;

$chat = $kickClient->chat($authToken->accessToken);

$messageResponse = $chat->sendMessage(SendChatMessageDTO::make(
    broadcaster_user_id: $authenticatedUser->userId,
    content: 'Hello, world!',
));

echo "Message sent successfully!" . PHP_EOL;
echo "Message ID: " . $messageResponse->messageId . PHP_EOL;
echo "Message Content: " . $messageResponse->isSent . PHP_EOL;
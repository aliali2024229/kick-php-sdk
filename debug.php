<?php

require_once __DIR__ . '/vendor/autoload.php';

use Danielhe4rt\KickSDK\KickClient;
use Danielhe4rt\KickSDK\OAuth\DTOs\AuthenticateDTO;
use Danielhe4rt\KickSDK\OAuth\DTOs\RedirectUrlDTO;
use Danielhe4rt\KickSDK\OAuth\Enums\KickOAuthScopesEnum;

$clientId = '01JPFTZ08SCGX0YJP91YRVCEEQ';
$clientSecret = '';

$kickClient = new KickClient(
    clientId: $clientId,
    clientSecret: $clientSecret,
);

$redirectUrlDTO = RedirectUrlDTO::make(
    clientId: $clientId,
    redirectUri: 'http://localhost:8000/oauth/kick',
    responseType: 'code',
    scopes: [KickOAuthScopesEnum::USER_READ, KickOAuthScopesEnum::EVENTS_SUBSCRIBE],
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

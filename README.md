<div align="center">

[![Tests](https://github.com/danielhe4rt/kick-php-sdk/actions/workflows/test.yml/badge.svg)](https://github.com/danielhe4rt/kick-php-sdk/actions/workflows/test.yml)

</div>

<h1 align="center"> Kick PHP SDK </h1>

<img src="./.github/assets/logo.png" width=90 align="left" />

A PHP SDK for interacting with the [Kick.com](https://kick.com) API. This SDK provides a simple and intuitive way to
authenticate with Kick's OAuth 2.0 implementation and interact with various Kick API endpoints.

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

```bash
composer require danielhe4rt/kick-php-sdk
```

## Usage

```php
use DanielHe4rt\KickSDK\KickClient;

$clientId = 'your-client-id';
$clientSecret = 'your-client-secret';

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
```

> See [examples](examples.php) for more usage examples.
> We also will be providing a Webhook handler soon. 

## Roadmap

- [x] Finish implementing all resources
- [x] Add comprehensive test coverage (90%+)
- [ ] Add more examples and documentation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Credits

- [danielhe4rt](https://github.com/danielhe4rt) - Creator and maintainer 
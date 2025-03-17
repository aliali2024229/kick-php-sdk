<?php

namespace Danielhe4rt\KickSDK\OAuth\Enums;


/**
 * Scopes for Kick API

 * @see https://docs.kick.com/getting-started/scopes
 *
 * @package Danielhe4rt\KickSDK\OAuth
 */
enum KickOAuthScopesEnum: string
{
    /**
     * @scope user:read
     * @summary Read user info
     * @description View user information in Kick including username, streamer ID, etc.
     */
    case USER_READ = 'user:read';

    /**
     * @scope channel:read
     * @summary Read channel info
     * @description View channel information in Kick including channel description, category, etc.
     */
    case CHANNEL_READ = 'channel:read';

    /**
     * @scope channel:write
     * @summary Update channel info
     * @description Update livestream metadata for a channel based on the channel ID.
     */
    case CHANNEL_WRITE = 'channel:write';

    /**
     * @scope chat:write
     * @summary Write to chat
     * @description Send chat messages and allow chatbots to post in your chat.
     */
    case CHAT_WRITE = 'chat:write';

    /**
     * @scope streamkey:read
     * @summary Read stream key
     * @description Read a user's stream URL and stream key.
     */
    case STREAMKEY_READ = 'streamkey:read';

    /**
     * @scope events:subscribe
     * @summary Subscribe to events
     * @description Subscribe to all channel events on Kick e.g. chat messages, follows, subscriptions.
     */
    case EVENTS_SUBSCRIBE = 'events:subscribe';

}


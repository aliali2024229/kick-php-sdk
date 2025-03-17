<?php

namespace Danielhe4rt\KickSDK\Events\Enums;

/**
 * Enum representing the different types of events that can be received from the Kick Webhook API.
 *
 * @description Request body payloads for Webhook API requests
 *
 * @see https://docs.kick.com/events/event-types
 */
enum KickEventTypeEnum: string
{
    /**
     * Chat Message Sent Event
     *
     * Headers:
     * - Kick-Event-Type: "chat.message.sent"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "message_id": "unique_message_id_123",
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "sender": {
     *     "is_anonymous": false,
     *     "user_id": 987654321,
     *     "username": "sender_name",
     *     "is_verified": false,
     *     "profile_picture": "https://example.com/sender_avatar.jpg",
     *     "channel_slug": "sender_channel"
     *   },
     *   "content": "This is a test message with emotes!",
     *   "emotes": [
     *     {
     *       "emote_id": "12345",
     *       "positions": [
     *         { "s": 0, "e": 7 }
     *       ]
     *     },
     *     {
     *       "emote_id": "67890",
     *       "positions": [
     *         { "s": 20, "e": 25 }
     *       ]
     *     }
     *   ]
     * }
     */
    case ChatMessageSent = 'chat.message.sent';

    /**
     * Channel Followed Event
     *
     * Headers:
     * - Kick-Event-Type: "channel.followed"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "follower": {
     *     "is_anonymous": false,
     *     "user_id": 987654321,
     *     "username": "follower_name",
     *     "is_verified": false,
     *     "profile_picture": "https://example.com/sender_avatar.jpg",
     *     "channel_slug": "follower_channel"
     *   }
     * }
     */
    case ChannelFollowed = 'channel.followed';

    /**
     * Channel Subscription Renewal Event
     *
     * Headers:
     * - Kick-Event-Type: "channel.subscription.renewal"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "subscriber": {
     *     "is_anonymous": false,
     *     "user_id": 987654321,
     *     "username": "subscriber_name",
     *     "is_verified": false,
     *     "profile_picture": "https://example.com/sender_avatar.jpg",
     *     "channel_slug": "subscriber_channel"
     *   },
     *   "duration": 3,
     *   "created_at": "2025-01-14T16:08:06Z"
     * }
     */
    case ChannelSubscriptionRenewal = 'channel.subscription.renewal';

    /**
     * Channel Subscription Gifts Event
     *
     * Headers:
     * - Kick-Event-Type: "channel.subscription.gifts"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "gifter": {
     *     "is_anonymous": false,
     *     "user_id": 987654321, // null if is_anonymous=true
     *     "username": "gifter_name", // null if is_anonymous=true
     *     "is_verified": false, // null if is_anonymous=true
     *     "profile_picture": "https://example.com/sender_avatar.jpg", // null if is_anonymous=true
     *     "channel_slug": "gifter_channel" // null if is_anonymous=true
     *   },
     *   "giftees":
     *   [
     *     {
     *       "is_anonymous": false,
     *       "user_id": 561654654,
     *       "username": "giftee_name",
     *       "is_verified": true,
     *       "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *       "channel_slug": "giftee_channel"
     *     }
     *   ],
     *   "created_at": "2025-01-14T16:08:06Z"
     * }
     */
    case ChannelSubscriptionGift = 'channel.subscription.gifts';

    /**
     * Channel Subscription Created Event
     *
     * Headers:
     * - Kick-Event-Type: "channel.subscription.new"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "subscriber": {
     *     "is_anonymous": false,
     *     "user_id": 987654321,
     *     "username": "subscriber_name",
     *     "is_verified": false,
     *     "profile_picture": "https://example.com/sender_avatar.jpg",
     *     "channel_slug": "subscriber_channel"
     *   },
     *   "duration": 1,
     *   "created_at": "2025-01-14T16:08:06Z"
     * }
     */
    case ChannelSubscriptionNew = 'channel.subscription.new';

    /**
     * Livestream Status Updated Event
     *
     * Headers:
     * - Kick-Event-Type: "livestream.status.updated"
     * - Kick-Event-Version: "1"
     *
     * Payload:
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "is_live": true,
     *   "title": "Stream Title",
     *   "started_at": "2025-01-01T11:00:00+11:00",
     *   "ended_at": null
     * }
     *
     * or
     *
     * {
     *   "broadcaster": {
     *     "is_anonymous": false,
     *     "user_id": 123456789,
     *     "username": "broadcaster_name",
     *     "is_verified": true,
     *     "profile_picture": "https://example.com/broadcaster_avatar.jpg",
     *     "channel_slug": "broadcaster_channel"
     *   },
     *   "is_live": false,
     *   "title": "Stream Title",
     *   "started_at": "2025-01-01T11:00:00+11:00",
     *   "ended_at": "2025-01-01T15:00:00+11:00"
     * }
     */
    case LivestreamStatusUpdated = 'livestream.status.updated';
}
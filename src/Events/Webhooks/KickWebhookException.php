<?php

namespace DanielHe4rt\KickSDK\Events\Webhooks;

use Exception;

class KickWebhookException extends Exception
{
    public static function invalidEventType(?string $eventType)
    {
        return new self('Invalid event type: '.$eventType ?? 'null');
    }

    public static function invalidEventVersion(int $eventVersion)
    {
        return new self('Invalid event version: '.$eventVersion);
    }
}

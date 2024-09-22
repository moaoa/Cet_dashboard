<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OneSignalNotifier
{
    private static $appId;
    private static $restApiKey;
    private static $baseUrl = "https://onesignal.com/api/v1/notifications";
    private static $client;

    public static function init()
    {
        self::$appId = env('ONESIGNAL_APP_ID');
        self::$restApiKey = env('ONESIGNAL_REST_API_KEY');

        if (!self::$appId || !self::$restApiKey) {
            throw new \Exception("OneSignal configuration not found in environment variables.");
        }

        self::$client = new Client();
    }

    public static function sendNotificationToUsers(array $subscriptions, $message)
    {
        if (sizeof($subscriptions) == 0)
            return;

        $payload = [
            'app_id' => self::$appId,
            "include_subscription_ids" => $subscriptions,
            'contents' => ['en' => $message, 'ar' => $message],
            'headings' => ['en' => "إعلان", 'ar' => "إعلان"],
        ];

        return self::sendNotification($payload);
    }

    public static function sendNotificationToAllUsers($message, $url)
    {
        $payload = [
            'app_id' => self::$appId,
            "included_segments" => ["Total Subscriptions"],
            'contents' => ['ar' => $message],
            'headings' => ['ar' => "إعلان"],
            'url' => $url
        ];

        return self::sendNotification($payload);
    }

    private static function sendNotification($payload)
    {
        if (!self::$appId || !self::$restApiKey) {
            self::init();
        }

        try {
            $response = self::$client->post(self::$baseUrl, [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Authorization' => 'Basic ' . self::$restApiKey
                ],
                'json' => $payload,
                'verify' => false // Only use this if you're having SSL verification issues
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw new \Exception("HTTP Request failed: " . $e->getMessage());
        }
    }
}

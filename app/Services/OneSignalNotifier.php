<?php

namespace App\Services;

class OneSignalNotifier
{
    private static $appId;
    private static $restApiKey;
    private static $baseUrl = "https://onesignal.com/api/v1/notifications";

    public static function init()
    {
        self::$appId = env('ONESIGNAL_APP_ID');
        self::$restApiKey = env('ONESIGNAL_REST_API_KEY');

        if (!self::$appId || !self::$restApiKey) {
            throw new \Exception("OneSignal configuration not found in environment variables.");
        }
    }

    public static function sendNotificationToUsers(array $subscriptions, $message, $heading = "Notification")
    {
        $payload = [
            'app_id' => self::$appId,
            "include_subscription_ids" => $subscriptions,
            'contents' => ['en' => $message],
            'headings' => ['en' => $heading]
        ];

        return self::sendNotification($payload);
    }

    public static function sendNotificationToAllUsers($message, $heading = "Notification")
    {
        $payload = [
            'app_id' => self::$appId,
            "included_segments" => ["Total Subscriptions"],
            'contents' => ['en' => $message],
            'headings' => ['en' => $heading]
        ];

        return self::sendNotification($payload);
    }


    private static function sendNotification($payload)
    {
        if (!self::$appId || !self::$restApiKey) {
            self::init();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$baseUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . self::$restApiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        return json_decode($response, true);
    }
}

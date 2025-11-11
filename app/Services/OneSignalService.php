<?php

namespace App\Services;

use GuzzleHttp\Client;

class OneSignalService
{
    protected $client;
    protected $appId;
    protected $restApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->appId = config('services.onesignal.app_id');
        $this->restApiKey = config('services.onesignal.rest_api_key');
    }

    public function sendNotification($message, $playerId, $data = [])
    {
        $response = $this->client->post('https://onesignal.com/api/v1/notifications', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->restApiKey,
            ],
            'json' => [
                'app_id' => $this->appId,
                'include_player_ids' => [$playerId],
                'headings' => ['en' => 'Reminder'],
                'contents' => ['en' => $message],
                'data' => $data,
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
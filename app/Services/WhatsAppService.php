<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected static $appkey = '89beefce-4a61-4529-831f-d35de597141e';
    protected static $authkey = 'bKibYKFlHIS0WrDINddsI9VeZ0SLXM0NYQQEDyZQ7je1I5hoEy';
    protected static $baseUrl = 'http://wa.mpdev.my.id/api/create-message';

    /**
     * Send a message via the WhatsApp API.
     *
     * @param string $phoneNumber The recipient's phone number.
     * @param string $message The message to send.
     * @return \Illuminate\Http\Client\Response
     */
    public static function sendMessage(string $phoneNumber, string $message)
    {
        $response = Http::get(self::$baseUrl, [
            'appkey' => self::$appkey,
            'authkey' => self::$authkey,
            'to' => $phoneNumber,
            'message' => $message,
        ]);

        // Log every attempt and its outcome
        Log::info('WhatsApp API Request Sent', [
            'to' => $phoneNumber,
            'status' => $response->status(),
            'response_body' => $response->body(),
        ]);

        if ($response->failed()) {
            Log::error('WhatsApp API request failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }

        return $response;
    }
}

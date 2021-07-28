<?php

namespace App\Services;

use GuzzleHttp\Client;

class HttpRequestService
{
    public static function send(string $method, string $url, array $options = [])
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->request($method, $url, $options);

            return [
                'status' => 'success',
                'code' => $response->getStatusCode(),
                'content' => $response->getBody()->getContents()
            ];
        } catch (\Exception $exception) {
            return [
                'status' => 'error',
                'code' => $exception->getCode(),
                'content' => $exception->getResponse() ? $exception->getResponse()->getBody()->getContents() : null,
                'message' => $exception->getMessage()
            ];
        }
    }
}

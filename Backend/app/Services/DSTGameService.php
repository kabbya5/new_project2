<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class DSTGameService
{
    protected Client $client;
    protected array $config;

    public function __construct()
    {
        $this->client = new Client();
        $this->config = config('services.dstgame');
    }

    public function launchGame(array $data): array
    {
        $postData = [
            "companyCode" => "001",
            "apiKey" => "KFRSy7Nr",
            "loginId" => "apimember1234",
            "verificationKey" => "ges",
            "providerCode" => "MARIOS",
            "gameCode" => "GS022",
            "gameType" => "SLOT"
        ];

        try {
            $response = $this->client->post($this->config['api_url'] .'api/agent/launchGame', [
                'json' => $postData,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (!$responseData) {
                throw new Exception("Invalid JSON response from DST Game API.");
            }

            dd($responseData);

        } catch (Exception $e) {
            return [
                'code' => '1',
                'message' => $e->getMessage(),
                'launchUrl' => null,
                'status' => 'FALSE'
            ];
        }
    }
}

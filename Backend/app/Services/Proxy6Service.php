<?php

namespace App\Services;

use GuzzleHttp\Client;

class Proxy6Service
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('PROXY6_API_KEY');
    }

    /**
     * Get available proxies from Proxy6 API
     */
    public function getProxies($status = 1) // 1=active
    {
        $client = new Client();
        $response = $client->get('https://proxy6.net/api/', [
            'query' => [
                'key' => $this->apiKey,
                'action' => 'getproxy',
                'status' => $status
            ],
            'timeout' => 30
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Test a URL via a given proxy
     */
    public function fetchUrlViaProxy(string $url, array $proxy)
    {
        // Determine protocol (http / https / socks)
        $protocol = strtolower($proxy['type'] ?? 'http');

        // IPv6 formatting if needed
        $ip = $proxy['ip'];
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = "[$ip]";
        }

        $proxyString = "{$proxy['login']}:{$proxy['password']}@$ip:{$proxy['port']}";

        $client = new Client([
            'proxy' => [
                'http'  => "$protocol://$proxyString",
                'https' => "$protocol://$proxyString",
            ],
            'timeout' => 30
        ]);

        try {
            $response = $client->get($url);
            return [
                'success' => true,
                'body' => (string) $response->getBody(),
                'proxy' => $proxy
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'proxy' => $proxy
            ];
        }
    }
}
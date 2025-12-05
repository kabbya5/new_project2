<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class BrightDataService
{
    /**
     * Create a Guzzle client with Bright Data proxy
     */
    public static function client(string $country = 'us', bool $forceIPv4 = false): Client
    {
        $username = config('proxy6.username');
        $password = config('proxy6.password');
        $ipv6     = config('proxy6.ipv6');
        $port     = config('proxy6.port');

        if (empty($username) || empty($password) || empty($ipv6) || empty($port)) {
            throw new \Exception("Proxy6 IPv6 configuration missing in .env or config");
        }

        // Wrap IPv6 in brackets
        $proxyIp = "[{$ipv6}]";
        $proxy   = "{$username}:{$password}@{$proxyIp}:{$port}";

        return new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'proxy' => [
                'http'  => "http://{$proxy}",
                'https' => "http://{$proxy}",
            ],
            'verify' => false,
            'headers' => [
                'User-Agent' => self::getUserAgent($country),
                'Accept'     => 'application/json',
                'Accept-Language' => self::getAcceptLanguage($country),
            ],
            'curl' => $forceIPv4 ? [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4] : [],
        ]);
    }

    /**
     * Make a request through Proxy6.net proxy with optional IPv4 fallback
     */
    public static function makeRequest(string $url, string $method = 'GET', array $options = [], string $country = 'us'): array
    {
        try {
            $client = self::client($country);
            $response = $client->request($method, $url, $options);
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return [
                'success' => true,
                'status_code' => $response->getStatusCode(),
                'body' => $body,
                'data' => $data,
                'headers' => $response->getHeaders(),
                'proxy_country' => $country,
            ];
        } catch (RequestException $e) {
            Log::warning("Proxy6 RequestException (trying IPv6)", [
                'message' => $e->getMessage(),
                'url' => $url,
                'country' => $country,
            ]);

            // Retry forcing IPv4
            try {
                $client = self::client($country, true);
                $response = $client->request($method, $url, $options);
                $body = (string) $response->getBody();
                $data = json_decode($body, true);

                return [
                    'success' => true,
                    'status_code' => $response->getStatusCode(),
                    'body' => $body,
                    'data' => $data,
                    'headers' => $response->getHeaders(),
                    'proxy_country' => $country,
                    'note' => 'Forced IPv4 fallback used',
                ];
            } catch (\Exception $e2) {
                Log::error('Proxy6 Exception (IPv4 fallback failed)', [
                    'message' => $e2->getMessage(),
                    'url' => $url,
                    'country' => $country,
                ]);

                return [
                    'success' => false,
                    'message' => 'Both IPv6 and IPv4 proxy requests failed: ' . $e2->getMessage(),
                    'status_code' => 500,
                    'proxy_country' => $country,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Proxy6 Exception', [
                'message' => $e->getMessage(),
                'url' => $url,
                'country' => $country,
            ]);

            return [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'status_code' => 500,
                'proxy_country' => $country,
            ];
        }
    }

    /**
     * POST request with JSON
     */
    public static function postJson(string $url, array $data = [], string $country = 'us'): array
    {
        return self::makeRequest($url, 'POST', ['json' => $data], $country);
    }

    /**
     * Attempt request with fallback countries
     */
    public static function makeRequestWithFallback(string $url, string $method = 'GET', array $options = [], array $countries = null): array
    {
        $countries = $countries ?? ['us', 'gb', 'ca', 'de'];

        foreach ($countries as $country) {
            $result = self::makeRequest($url, $method, $options, $country);
            if ($result['success']) {
                Log::info('Bright Data request succeeded', ['country' => $country, 'url' => $url]);
                return $result;
            }
        }

        return [
            'success' => false,
            'error' => 'All proxy countries failed: ' . implode(', ', $countries),
        ];
    }

    /**
     * Test connection
     */
    public static function test(string $country = 'us'): array
    {
        return self::makeRequest('https://brdtest.com/info', 'GET', [], $country);
    }

    /**
     * Country-specific User-Agent
     */
    private static function getUserAgent(string $country): string
    {
        $agents = [
            'us' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'gb' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'de' => 'Mozilla/5.0 (X11; Linux x86_64)',
        ];
        return $agents[$country] ?? $agents['us'];
    }

    /**
     * Country-specific Accept-Language
     */
    private static function getAcceptLanguage(string $country): string
    {
        $languages = [
            'us' => 'en-US,en;q=0.9',
            'gb' => 'en-GB,en;q=0.9',
            'de' => 'de-DE,de;q=0.9,en;q=0.8',
            'fr' => 'fr-FR,fr;q=0.9,en;q=0.8',
            'br' => 'pt-BR,pt;q=0.9,en;q=0.8',
        ];
        return $languages[$country] ?? 'en-US,en;q=0.9';
    }

    /**
     * Supported countries
     */
    public static function getAvailableCountries(): array
    {
        return ['us', 'gb', 'de', 'ca', 'fr', 'br'];
    }
}

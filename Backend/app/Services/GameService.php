<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Game;
use App\Models\User;
use GuzzleHttp\Client;
use Exception;

class GameService
{
    protected Client $client;
    protected array $config;

    public function __construct()
    {
        $this->client = new Client();
        $this->config = config('services.game');
    }

    public function getProviders(){
        try{
            $response = $this->client->get($this->config['api_url'].'/providers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config['agent_token'],
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (Exception $e) {
            return [
                'status' => 0,
                'data'   => [],
                'msg'    => 'Error fetching providers: ' . $e->getMessage(),
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getGames($providerId = 61): array{
        try {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];

            // Add provider query parameter if provided
            if ($providerId !== null) {
                $options['query'] = [
                    'provider' => $providerId
                ];
            }

            $response = $this->client->get($this->config['api_url'].'/games', $options);

        } catch (Exception $e) {
            return [
                'status' => 0,
                'data'   => [],
                'msg'    => 'Error fetching games: ' . $e->getMessage(),
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function launchGame(Game $game, $user_id): array  {
        $user = User::find($user_id);
        $currency = Currency::where('currency_code',$user->currency)->first();

        try {
            $response = $this->client->post($this->config['api_url'].'/game_launch', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'agentToken'    => $this->config['agent_token'],
                    'secretKey'    =>  $this->config['agent_secret_key'],
                    'user_code'     => $user->user_name, // $user_code,
                    'game_code'     => $game->game_code,
                    'game_original' => true,
                    "user_balance"  => $user->balance ? $user->balance / $currency->brl_rate : 0,
                    'lang'          => 'en',
                ],
            ]);
        } catch (Exception $e) {
            return [
                'status' => 0,
                'msg'    => 'Error launching game: ' . $e->getMessage(),
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function playSports($user_id): array  {

        $user = User::find($user_id);
        $currency = Currency::where('currency_code',$user->currency)->first();

        try {
            $response = $this->client->post($this->config['api_url'].'/game_launch', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'agentToken'    => $this->config['agent_token'],
                    'secretKey'    =>  $this->config['agent_secret_key'],
                    'user_code'     => $user->user_name, // $user_code,
                    'game_code'     => 'sport',
                    'game_original' => true,
                    "user_balance"  => $user->balance ? $user->balance / $currency->brl_rate : 0,
                    'lang'          => 'en',
                ],
            ]);
        } catch (Exception $e) {
            return [
                'status' => 0,
                'msg'    => 'Error launching game: ' . $e->getMessage(),
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    public function balance($user_id){
        try {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];

            // Add provider query parameter if provided
            if ($user_id !== null) {
                $options['query'] = [
                    'type' => 'BALANCE',
                    'user_code' => '123',
                ];
            }

            $response = $this->client->get('https://api.playfivers.com/webhook', $options);

        } catch (Exception $e) {
            return [
                'status' => 0,
                'data'   => [],
                'msg'    => 'Error fetching games: ' . $e->getMessage(),
            ];
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}

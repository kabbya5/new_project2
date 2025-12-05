<?php 

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Exception;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OroPlayService{
    protected Client $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.oroplay.base_url');
        $this->clientId = config('services.oroplay.client_id');
        $this->clientSecret = config('services.oroplay.client_secret');

    }

    private function requestPost(string $endpoint, array $data, int $timeout = 10)
    {
        try {
            $response = $this->client->post($this->baseUrl . $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getBearerToken(),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ],
                'json' => $data,
                'timeout' => $timeout,
            ]);

            return json_decode($response->getBody(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody(), true);
            }

            throw new \Exception($e->getMessage());
        }
    }


    public function getToken()
    {
        try {
    $response = $this->client->post($this->baseUrl . '/auth/createtoken', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'json' => [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ],
    ]);

    $body = $response->getBody()->getContents();
    $data = json_decode($body, true);

    if (!$data || !isset($data['token'])) {
        throw new \Exception('Invalid API Response: ' . $body);
    }

    cache([
        'oroplay_token' => $data['token'],
        'oroplay_token_expire' => now()->addSeconds($data['expiration'] ?? 3600)
    ]);

    return $data['token'];

} catch (\GuzzleHttp\Exception\RequestException $e) {
    // Guzzle HTTP errors
    $response = $e->getResponse();
    $status = $response ? $response->getStatusCode() : null;
    $body = $response ? (string)$response->getBody() : null;

    return [
        'error' => true,
        'type' => 'RequestException',
        'message' => $e->getMessage(),
        'status' => $status,
        'body' => $body,
    ];

} catch (\GuzzleHttp\Exception\ConnectException $e) {
    // Connection errors like timeout / server down
    return [
        'error' => true,
        'type' => 'ConnectException',
        'message' => $e->getMessage(),
    ];

} catch (\Exception $e) {
    // Any other PHP exceptions
    return [
        'error' => true,
        'type' => 'GeneralException',
        'message' => $e->getMessage(),
    ];
}
    }



    protected function getBearerToken(){
        if(cache()->has('oroplay_token')){
            return cache('oroplay_token');
        }

        return $this->getToken();
    }

    public function createUser($userCode) {
        return $this->requestPost('/user/create', ['userCode' => $userCode]);
    }

    // public function getUserBalance($userCode){
    //     return $this->requestPost('/user/balance', ['userCode' => $userCode]);
    // }

    public function deposite($userCode,$balance, $orderNo){
        $data = ['userCode' => $userCode, 'balance' => $balance, 'orderNo' => $orderNo];
        $data['vendorCode'] = $this->clientId;
        return $this->requestPost('/user/deposit', $data);
    }

    public function withdraw($userCode,$balance, $orderNo){
        $data = ['userCode' => $userCode, 'balance' => $balance];
        $data['vendorCode'] = 'slot-jili';

        return $this->requestPost('/user/withdraw', $data);
    }

    public function withdrawall($userCode){
        $data['vendoerCode'] = $this->clientId;
        
        return $this->requestPost('/user/withdraw-all', $data);
    }

    public function launch_url($data)
    {   
        $userCode = $data['userCode'];
        $data['lobbyUrl'] = 'https://www.luckbuzz99.com/';
        $data['language'] = 'en';
        $data['vendorCode'] = $this->clientId;

        return $this->requestPost('/launch', $data);
    }

    public function getUserBalance($userCode){
        $authHeader = base64_encode($this->clientId . ':' . $this->clientSecret);
        
        try{
            $response = $this->client->post($this->baseUrl . '/balance',[
                'headers' => [
                    'Authorization' => 'Basic ' . $authHeader,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'appilication/json',
                ],
                'json' => [
                    'userCode' => $userCode,
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            Log::info("Balance API Response", $body);

            if(isset($body['success']) && $body['success'] === true){
                $balance = $body['message'];

                $user = User::where('user_name', $userCode)->first();

                $user->update(['balance' => $balance]);

                return [
                    'balance' => $body['message'],  // user balance
                    'errorCode' => $body['errorCode']
                ];
            }else {
                return [
                    'balance' => 0,
                    'error' => $body['message'] ?? 'Unknown error',
                    'errorCode' => $body['errorCode'] ?? -1
                ];
            }
        }catch(\Exception $e){
             Log::error("Balance API Error", [
                'message' => $e->getMessage(),
                'userCode' => $userCode,
            ]);
            return [
                'error' => [$e->getMessage()],
            ];
        }

    }
}
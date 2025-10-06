<?php

namespace App\Services;

class LPGService
{
    protected $appId;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->appId = config('services.lpg.app_id');
        $this->secretKey = config('services.lpg.secret_key');
        $this->baseUrl = config('services.lpg.base_url');
    }

    private function md5Sign($data)
    {
        ksort($data);
        $string = urldecode(http_build_query($data));
        $string = trim($string) . "&key=" . $this->secretKey;
        return strtoupper(md5($string));
    }

    public function createOrder($amount, $tradeType, $user, $order_sn)
    {
        $data = [
            "app_id"     => $this->appId,
            "trade_type" => $tradeType,
            'user_id' => $user->user_name,
            "order_sn"   => $order_sn,
            "money"      => $amount *100,
            "notify_url" => url('/api/deposite/notification'),
            "ip"         => '139.180.137.164',
            "remark"     => $user->user_name,
        ];

        $data['sign'] = $this->md5Sign($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . "/order/create");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function payOutOrder($amount, $tradeType, $phone_number, $user, $order_sn){
        $data = [
            "app_id"     => $this->appId,
            'name' =>   $tradeType,
            'currency'  =>  strtoupper($user->currency),
            "trade_type" => $tradeType,
            "order_sn"   =>  $order_sn,
            "money"      => intval($amount * 100),
            'card_number'=> $phone_number,
            "notify_url" =>  url('withdraw.notification'),
            "ip"         => '139.180.137.164',
        ];

        $data['sign'] = $this->md5Sign($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . "/deposit/create");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}

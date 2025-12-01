<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proxy6.net Configuration
    |--------------------------------------------------------------------------
    |
    | All credentials and proxy configuration for Proxy6.net.
    |
    */

    'host' => env('PROXY6_HOST', '45.145.57.232'),
    'port' => env('PROXY6_PORT', 10677),
    'username' => env('PROXY6_USER', 'pmFjRr'),
    'password' => env('PROXY6_PASS', 'GVU8ZM'),
    'type' => env('PROXY6_TYPE', 'https'), // https / http / socks
    'ipv6' => env('PROXY6_IPV6', '2a10:6d41:bd3c:7bb5:14a1:e016:5851:c76f'),
    'api_key' => env('PROXY6_API_KEY', '3e0c0b234f-728a3419ca-7a1020d50e'),

];
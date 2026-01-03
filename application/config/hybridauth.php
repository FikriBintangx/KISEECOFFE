<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| HybridAuth Configuration
|--------------------------------------------------------------------------
|
| You can obtain these keys by registering your application with the 
| respective providers.
|
| Google: https://console.developers.google.com/
| Facebook: https://developers.facebook.com/
|
*/

$config['hybridauth'] = [
    'callback' => base_url('auth/oauth_callback'), // Start with a default callback
    'providers' => [
        'Google' => [
            'enabled' => true,
            'keys'    => ['id' => 'YOUR_GOOGLE_CLIENT_ID', 'secret' => 'YOUR_GOOGLE_CLIENT_SECRET'],
        ],
        'Facebook' => [
            'enabled' => true,
            'keys'    => ['id' => 'YOUR_FACEBOOK_APP_ID', 'secret' => 'YOUR_FACEBOOK_APP_SECRET'],
            'trustForwarded' => false
        ]
    ],
    'debug_mode' => ENVIRONMENT === 'development',
    'debug_file' => APPPATH . 'logs/hybridauth.log',
];

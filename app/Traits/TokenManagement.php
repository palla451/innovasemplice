<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait TokenManagement
{
    /**
     * @param $email
     * @param $password
     * @return array
     */
    public function getTokenRefreshToken($email, $password, $osClient): array
    {
        $baseUrl = url('/');
        $response = Http::post("{$baseUrl}/oauth/token", [
            'username' => $email,
            'password' => $password,
            'client_id' => $osClient->id,
            'client_secret' => $osClient->secret,
            'grant_type' => 'password',
            'scope' => '',
        ]);

        return  json_decode($response->getBody(), true);
    }

    /**
     * @param $refreshToken
     * @return mixed
     */
    public function getRefreshToken($refreshToken, $osClient): array
    {
        $baseUrl = url('/');
        $response = Http::post("{$baseUrl}/oauth/token", [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $osClient->id,
            'client_secret' => $osClient->secret,
            'scope' => '',
        ]);

        return  json_decode($response->getBody(), true);
    }
}

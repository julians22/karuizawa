<?php

namespace App\Libraries\Api\Accurate;

use Carbon\Carbon;
use Http;

class Oauth
{
    protected string $appToken;
    protected string $appKey;
    protected string $appSignature;

    public function __construct()
    {
        $this->appToken = config('accurate.auth.app_token');
        $this->appKey = config('accurate.auth.app_key');
        $this->appSignature = config('accurate.auth.signature');
    }

    public function makeSignature()
    {
        $url = "https://account.accurate.id/api/api-token.do";
        $date = Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s');
        $secret = $this->getSignature($date);

        $headers = [
            'Authorization' => 'Bearer ' . $this->appToken,
            'X-Api-Timestamp' => $date,
            'X-Api-Signature' => $secret
        ];

        $response = Http::withHeaders($headers)
            ->post($url, []);

        if ($response->status() == 200) {
            $response = $response->json();

            $data = $response['d'];

            $application = $data['application'];
            $sign = $secret;

            $stores = [
                'application' => $application,
                'sign' => $sign,
                'timestamp' => $date
            ];

            cache()->put('accurate_auth', $stores, now()->addMinutes(10));
            return $stores;
        }
    }

    public function authInfo()
    {
        $cachedAuth = cache()->get('accurate_auth');

        if ($cachedAuth) {
            $sign = $cachedAuth['sign'];
            $timestamp = $cachedAuth['timestamp'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->appToken,
                'X-Api-Timestamp' => $timestamp,
                'X-Api-Signature' => $sign,
            ])->get('https://account.accurate.id/api/auth-info.do');

            if ($response->status() == 200) {
                $response = $response->json();
                return [
                    'timestamp' => $timestamp,
                    'signature' => $sign,
                ];
            }
            $this->makeSignature();
            return;
        }
        $createdSign = $this->makeSignature();
        return [
            'timestamp' => $createdSign['timestamp'],
            'signature' => $createdSign['sign'],
        ];
    }


    private function getSignature($string) : string
    {
        $signature = hash_hmac('sha256', $string, $this->appSignature);
        return $signature;
    }
}

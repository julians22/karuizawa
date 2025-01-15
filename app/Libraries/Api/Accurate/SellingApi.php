<?php

// Base Url: /api/sales-invoice
// endpoint_a: create-down-payment.do
// endpoint_b: save.do

use App\Libraries\Api\Accurate\Oauth;

class SellingApi
{
    protected string|null $signature;
    protected string|null $timestamp;
    protected string|null $appToken;
    protected string|null $appUrl;

    public function __construct()
    {
        $this->getSign();
        $this->appToken = config('accurate.auth.app_token');
        $this->appUrl = config('accurate.auth.app_url');
    }

    private function getSign(){
        $cachedAuth = cache()->get('accurate_auth');

        if ($cachedAuth) {
            $this->signature = $cachedAuth['sign'];
            $this->timestamp = $cachedAuth['timestamp'];
        } else {
            $oauth = new Oauth();
            $oauth->makeSignature();
            $cachedAuth = cache()->get('accurate_auth');
            $this->signature = $cachedAuth['sign'];
            $this->timestamp = $cachedAuth['timestamp'];
        }
    }

    public function createOrder() 
    {
        
    }


}

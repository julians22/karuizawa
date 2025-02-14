<?php


namespace App\Jobs\Accurate\Traits;


trait AccurateAccess
{

    /**
     * Get the access token from cache
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return cache()->get('accurate_token')['access_token'];
    }

    /**
     * Get the session id from cache
     *
     * @return string
     */
    public function getSessionId(): string
    {
        return cache()->get('accurate_db')['session'];
    }

    /**
     * Get the headers for the request
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'X-Session-ID' => $this->getSessionId(),
        ];
    }

}

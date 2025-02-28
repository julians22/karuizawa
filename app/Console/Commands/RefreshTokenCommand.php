<?php

namespace App\Console\Commands;

use Cache;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RefreshTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-token-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accurateToken = cache()->get('accurate_token');

        // check if token is exist
        if ($accurateToken) {
            $this->info('Refreshing token...');

            // check ttl of token
            $tokenTtl = Cache::getTTL('accurate_token');

            $ttlToTime = Carbon::now()->addSeconds($tokenTtl);
            $now = Carbon::now();


            $this->info('Token will be expired at: ' . $ttlToTime);
            $this->info('Current time: ' . $now);

            // check if ttl is less than 1 day
            if ($now->diffInDays($ttlToTime) <= 1) {
                $this->info('Token will be expired in less than 1 day.');
                $this->info('Refreshing token...');
                $refreshToken = cache()->get('accurate_token')['refresh_token'];

                $this->info('Refreshing token with refresh token: ' . $refreshToken);

                try {
                    $this->refreshToken($refreshToken);
                } catch (\Exception $e) {
                    $this->error('Failed to refresh token.');
                }

                $this->info('Token has been refreshed.');

                $newToken = cache()->get('accurate_token');

                $this->info('New token: ' . json_encode($newToken));

            } else {
                $this->info('Token is still valid.');
            }



        } else {
            $this->error('Token not found.');
        }

    }

    /**
     * @return mixed
     */
    private function refreshToken($refreshToken){
        $bearer = base64_encode(env('ACCURATE_CLIENT_KEY') . ":" . env('ACCURATE_CLIENT_SECRET'));

        $url = "https://account.accurate.id/oauth/token";

        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        // Post data with bearer token & json content type
        $headers = [
            'Authorization' => 'Basic ' . $bearer
        ];

        $response = Http::withHeaders($headers)
            ->asForm()
            ->post($url, $data);

        if ($response->status() != 200) {
            $usedData = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'url' => $url,
                'headers' => $headers,
            ];

            $merge = array_merge($usedData, $response->json());

            activity()
                ->withProperties($merge)
                ->log('Failed to refresh token.');

            return false;
        }

        $response = $response->json();

        // put cache 14 days from now
        cache()->put('accurate_token', $response, 1295999);

        activity()
            ->withProperties($response)
            ->log('Token has been refreshed.');

        return true;
    }
}

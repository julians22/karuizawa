<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\Api\Accurate\Oauth;
use Http;
use Illuminate\Http\Request;
use Redirect;

class SystemInformationController extends Controller
{
    public function index()
    {
        $token = $this->retrieveCachedToken();
        $dbList = $this->accurateDbList();
        $dbInfo = cache()->get('accurate_db');

        return view('backend.system-information.index', ['token' => $token, 'dbList' => $dbList, 'dbInfo' => $dbInfo]);
    }

    public function reloadAuthCache(Oauth $oauth)
    {
        $oauth->makeSignature();
        return redirect()->route('admin.system-information.index')->withFlashSuccess(__('The authentication cache was successfully reloaded.'));
    }

    public function accurate(){
        $url = env('ACCURATE_AUTHORIZATION_URL');
        $client = env('ACCURATE_CLIENT_KEY');

        $redirect = env('ACCURATE_REDIRECT_URI');
        $scopes = config('accurate.auth.scopes');
        $scopes = implode('%20', $scopes);

        return redirect()->to($url . "?client_id={$client}&redirect_uri={$redirect}&response_type=code&scope={$scopes}");
    }

    public function accurateCallback(){

        // Client ID:Client Secret
        $bearer = base64_encode(env('ACCURATE_CLIENT_KEY') . ":" . env('ACCURATE_CLIENT_SECRET'));
        $code = request()->get('code');

        $url = "https://account.accurate.id/oauth/token";

        $data = [
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        // Post data with bearer token & json content type
        $headers = [
            'Authorization' => 'Basic ' . $bearer
        ];

        $response = Http::withHeaders($headers)
            ->asForm()
            ->post($url, $data);

        if ($response->status() != 200) {
            return redirect()->route('admin.system-information.index')->withFlashDanger(__('Failed to authenticate with Accurate., ; ' . json_encode($response->json())));
        }

        $response = $response->json();

        cache()->put('accurate_token', $response, 1295999);

        return redirect()->route('admin.system-information.index')->withFlashSuccess(__('The authentication cache was successfully reloaded.'));
    }

    protected function accurateDbList()
    {
        // Get db list
        $dbList = cache()->get('accurate_db_list');

        if (!$dbList) {

            $token = $this->retrieveCachedToken();
            if (!$token) {
                return [];
            }

            $url = "https://account.accurate.id/api/db-list.do";

            $headers = [
                'Authorization' => 'Bearer ' . $token['access_token']
            ];

            $response = Http::withHeaders($headers)
                ->get($url);

            $dbList = $response->json();
            cache()->put('accurate_db_list', $dbList);
            $dbList["source"] = "api";

        }else{
            $dbList["source"] = "cache";
        }

        // Get Open DB
        $token = $this->retrieveCachedToken();
        $cachedDb = cache()->get('accurate_db');
        if (!$cachedDb) {
            // Get Open DB
            $headers = [
                'Authorization' => 'Bearer ' . $token['access_token']
            ];

            $url = "https://account.accurate.id/api/open-db.do?id=" . config('accurate.auth.db');
            $response = Http::withHeaders($headers)
                ->get($url);

            cache()->put('accurate_db', $response->json(), 1295999);
        }

        return $dbList;
    }

    function retrieveCachedToken() {
        if (cache()->has('accurate_token')) {
            return cache()->get('accurate_token');
        }
        return null;
    }
}

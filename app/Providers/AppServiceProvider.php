<?php

namespace App\Providers;

use Cache;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            Cache::macro('getTTL', function (string $key): ?int {
                $fs = new class extends FileStore {
                    public function __construct()
                    {
                        parent::__construct(App::get('files'), config('cache.stores.file.path'));
                    }

                    public function getTTL(string $key): ?int
                    {
                        return $this->getPayload($key)['time'] ?? null;
                    }
                };

                return $fs->getTTL($key);
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // set timezone
        date_default_timezone_set('Asia/Jakarta');
    }
}

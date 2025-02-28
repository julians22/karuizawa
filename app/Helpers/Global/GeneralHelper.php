<?php

use Carbon\Carbon;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     *
     * @param $time
     * @return Carbon
     *
     * @throws Exception
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function homeRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            }

            if (auth()->user()->isUser()) {
                return 'frontend.user.dashboard';
            }

            if (auth()->user()->isCrew()) {
                return 'crew.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if (! function_exists('price_format')) {
    /**
     * Format the price.
     *
     * @param $price
     * @return string
     */
    function price_format($price, $digit = 0)
    {
        return 'Rp ' . number_format($price, $digit, ',', '.');
    }
}

if (! function_exists('is_older_month_year')) {

    /**
     * Check if the given month and year is older than the current month and year.
     *
     * @param string $month
     * @param string $year
     * @return bool
     */
    function is_older_month_year($month, $year)
    {
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');

        if ($year < $currentYear) {
            return true;
        }

        if ($year == $currentYear) {
            if ($month < $currentMonth) {
                return true;
            }
        }

        return false;
    }

}



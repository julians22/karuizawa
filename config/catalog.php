<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Online Sellable Categories
    |--------------------------------------------------------------------------
    |
    | Category slugs that are currently allowed to be sold through the
    | online/frontend catalog API. Keep this data-driven (config/env) instead
    | of hardcoding category names in controllers/services, so the business
    | can enable more categories later without a code change.
    |
    */
    'online_categories' => array_filter(explode(',', env('CATALOG_ONLINE_CATEGORIES', 'perfume'))),

    'default_per_page' => 12,

    'max_per_page' => 50,
];

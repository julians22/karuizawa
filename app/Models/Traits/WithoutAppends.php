<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait WithoutAppends
{
    public static $withoutAppends = false;

    public static function bootWithoutAppends()
    {
        if (self::$withoutAppends) {
            static::retrieved(function ($model) {
                $model->setAppends([]);
            });
        }
    }
}

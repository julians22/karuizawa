<?php

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid as PackageUuid;

trait Uniqueid
{
    protected static function bootUniqueid()
    {
        static::creating(function ($model) {
            $model->uuid = PackageUuid::uuid4()->toString();
        });
    }
}

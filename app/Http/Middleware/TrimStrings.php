<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Support\Str;

/**
 * Class TrimStrings.
 */
class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
        '*.embroidery.initialName.z',
    ];

    /**
     * Transform the given value.
     *
     * Supports wildcard patterns in the $except array so nested payload
     * keys (e.g. embroidery initial name) can opt out of trimming.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (Str::is($this->except, $key)) {
            return $value;
        }

        return parent::transform($key, $value);
    }
}

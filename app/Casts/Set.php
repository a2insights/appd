<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Set implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @return \Illuminate\Contracts\Database\Eloquent\CastsAttributes<\Illuminate\Support\Stringable, string|\Stringable>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                return isset($value) && $value ? explode(',', $value) : [];
            }

            public function set($model, $key, $value, $attributes)
            {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }

                return isset($value) ? (string) $value : null;
            }
        };
    }
}

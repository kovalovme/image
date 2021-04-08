<?php


namespace Kovalovme\Image\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Kovalovme\Image\PathsBag;

class PathsCast implements CastsAttributes
{
    public function get($model, string $key, $values, array $attributes): PathsBag
    {
        return new PathsBag($model, $values);
    }

    public function set($model, string $key, $values, array $attributes)
    {
        if (is_array($values)) {
            return json_encode($values);
        }

        return $values;
    }
}
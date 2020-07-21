<?php

declare(strict_types=1);

namespace App\Helpers;


class JsonHelper
{
    public static function alreadyExists(array $array, callable $callback)
    {
        foreach ($array as $item) {
            if ($callback($item)) {
                return true;
            }
        }
        return false;
    }
}
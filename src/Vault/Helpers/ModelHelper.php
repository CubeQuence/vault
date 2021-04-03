<?php

declare(strict_types=1);

namespace Vault\Helpers;

final class ModelHelper
{
    public static function camelize(array $array): array
    {
        $return = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::camelize(array: $value);
            }

            $return[self::camelizeKey(key: $key)] = $value;
        }

        return $return;
    }

    private static function camelizeKey(string | int $key): string | int
    {
        if (! is_string($key)) {
            return $key;
        }

        $camelizedKey = str_replace(
            search: [' ', '_', '-'],
            replace: '',
            subject: ucwords($key, ' _-')
        );

        return lcfirst($camelizedKey);
    }
}

<?php

namespace  Carlin\LaravelDataSwagger\Helper;

class Str
{
    public static function camel(mixed $value, bool $lcFirst = true): string
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));

        return $lcFirst ? lcfirst($str) : ucfirst($str);
    }

    public static function snake(mixed $value, $delimiter = '_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $delimiter . '$2', $value));
    }
}

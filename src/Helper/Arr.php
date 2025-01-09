<?php

declare(strict_types=1);

namespace Carlin\LaravelDataSwagger\Helper;

class Arr extends \Illuminate\Support\Arr
{
    /**
     * 递归转驼峰数组
     *
     * @author: whj
     *
     * @date  : 2023/4/14 16:24
     */
    public static function snakeToCamel(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[Str::camel($key)] = self::snakeToCamel($value);
            } else {
                $result[Str::camel($key)] = $value;
            }
        }

        return $result;
    }

    /**
     * 递归转下划线数组
     */
    public static function camelToSnake(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[Str::snake($key)] = self::camelToSnake($value);
            } else {
                $result[Str::snake($key)] = $value;
            }
        }

        return $result;
    }
}

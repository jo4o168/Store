<?php

namespace App\Http\Helpers;

class StringHelper
{
    public static function camelizeDromedary($input, $separator = '_'): string
    {
        return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
    }

    public static function camelize($input, $separator = '_'): string
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    public static function loadNamespaceByClass($model): string
    {
        $dirs = glob('../app/Models/*/*');
        $dirs = array_merge($dirs, glob('../app/Models/*/*/*'));

        $result = array_map(function ($dir) use ($model) {
            if (strpos($dir, $model)) {
                return ucfirst(str_replace(
                    '/',
                    '\\',
                    str_replace(['../', '.php'], '', $dir)
                ));
            }
        }, array_filter($dirs, function ($dir) use ($model) {
            return strpos($dir, $model);
        }));
        return current($result);
    }


    public static function capitalize(string $value): string
    {
        return ucfirst(strtolower($value));
    }


    public static function capitalizeAll(string $value): string
    {
        return ucwords(strtolower($value));
    }

    public static function truly($value): bool
    {
        return isset($value) && $value !== '';
    }

    public static function passwordGenerate(int $length = 8): string
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*'), 0, $length);
    }
}

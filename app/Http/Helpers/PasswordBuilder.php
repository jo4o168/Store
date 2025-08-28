<?php

namespace App\Http\Helpers;

class PasswordBuilder
{
    private const string UPPER_LETTERS = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
    private const string LOWER_LETTERS = "abcdefghijklmnopqrstuvyxwz";
    private const string INT_NUMBERS = "0123456789";
    private const string SYMBOL_CHAR = "!@#$%¨&*()_+=";

    public static function generate($size = 12): string
    {
        $size = max($size, 8);
        $password = str_shuffle(self::UPPER_LETTERS);
        $password .= str_shuffle(self::LOWER_LETTERS);
        $password .= str_shuffle(self::INT_NUMBERS);
        $password .= str_shuffle(self::SYMBOL_CHAR);
        return substr(str_shuffle($password), 0, $size);
    }
}

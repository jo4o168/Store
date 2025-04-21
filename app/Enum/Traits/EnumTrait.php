<?php

namespace App\Enum\Traits;

trait EnumTrait
{
    public static function getNameByValue($value): string
    {
        $name = array_map(function ($item) use ($value) {
            if ($item->value === $value) {
                return $item->name;
            }
            return null;
        }, self::cases());

        return current(array_filter($name, fn($item) => !is_null($item)));

    }

    public static function getNamesByValues(array $values): array
    {
        $cases = array_filter(self::cases(), fn($case) => in_array($case->value, $values));
        return array_map(fn($case) => $case->name, $cases);
    }

    public static function values(): array
    {
        return array_map(
            fn($case) => $case instanceof \BackedEnum ? $case->value : $case->name,
            self::cases()
        );
    }

    public static function toArray(): array
    {
        return array_combine(
            array_map(fn($case) => $case->name, self::cases()),
            array_map(fn($case) => $case->value, self::cases())
        );
    }

    public static function toArrayInverter(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->name, self::cases())
        );
    }
}

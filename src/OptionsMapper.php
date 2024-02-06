<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Options\LengthOption;


class OptionsMapper {

    public static array $optionMethods = [
        "minLength" => [LengthOption::class, "minLength"],
        // ... other mappings ...
    ];

    public static array $availableOptions = [
        LengthOption::class,
        // ... other options ...
    ];

    public function __construct() {
        $this->optionMethods = [
            "minLength" => [LengthOption::class, "minLength"]
        ];

        $this->availableOptions = [
            LengthOption::class,
        ];
    }

    public static function GetOptionMethodByName(string $optionName): array {
        if (!array_key_exists($optionName, self::$optionMethods)) {
            throw new \InvalidArgumentException("Option method not found: $optionName");
        }
        return self::$optionMethods[$optionName];
    }

    public static function GetAvailableOptions(): array {
        return self::$availableOptions;
    }


}
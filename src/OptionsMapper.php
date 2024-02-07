<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Options\LengthOption;
use Maestroerror\EloquentRegex\Options\NumberOption;
use Maestroerror\EloquentRegex\Options\CharacterOption;

/**
 * Class OptionsMapper
 *
 * Maps string identifiers to specific option class methods.
 * This class is responsible for providing a bridge between string-based option names
 * and their corresponding concrete implementation in various option classes.
 */
class OptionsMapper {

    /**
     * @var array Mapping of option names to their corresponding class and method.
     */
    public static array $optionMethods = [
        "minLength" => [LengthOption::class, "minLength"],
        "maxLength" => [LengthOption::class, "maxLength"],
        "length" => [LengthOption::class, "exactLength"],
        "minNumbers" => [NumberOption::class, "setMinValue"],
        "maxNumbers" => [NumberOption::class, "setMaxValue"],
        "numberAmount" => [NumberOption::class, "setExactValue"],
        "allowChars" => [CharacterOption::class, "allow"],
        "excludeChars" => [CharacterOption::class, "exclude"],
        "minUppercase" => [CharacterOption::class, "minUppercase"],
        "minLowercase" => [CharacterOption::class, "minLowercase"],
        // ... other mappings ...
    ];

    /**
     * @var array List of available option classes.
     */
    public static array $availableOptions = [
        LengthOption::class,
        // ... other options ...
    ];

    /**
     * Retrieves the option method (class and method pair) by its name.
     *
     * @param string $optionName The name of the option method.
     * @return array An array containing the class and method for the specified option.
     * @throws \InvalidArgumentException If the option name does not exist in the mapping.
     */
    public static function GetOptionMethodByName(string $optionName): array {
        if (!array_key_exists($optionName, self::$optionMethods)) {
            throw new \InvalidArgumentException("Option method not found: $optionName");
        }
        return self::$optionMethods[$optionName];
    }

    /**
     * Returns a list of available option classes.
     *
     * @return array An array of available option classes.
     */
    public static function GetAvailableOptions(): array {
        return self::$availableOptions;
    }
}

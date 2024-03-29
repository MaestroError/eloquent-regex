<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Options\LengthOption;
use Maestroerror\EloquentRegex\Options\NumberOption;
use Maestroerror\EloquentRegex\Options\CharacterOption;
use Maestroerror\EloquentRegex\Options\IPv6Option;
use Maestroerror\EloquentRegex\Options\CharOption;
use Maestroerror\EloquentRegex\Options\FileOption;
use Maestroerror\EloquentRegex\Options\FileExistsOption;
use Maestroerror\EloquentRegex\Options\SpecificCurrenciesOption;
use Maestroerror\EloquentRegex\Options\PathTypeOption;
use Maestroerror\EloquentRegex\Options\CountryCodeOption;
use Maestroerror\EloquentRegex\Options\ContainSpacesOption;
use Maestroerror\EloquentRegex\Options\DomainSpecificOption;
use Maestroerror\EloquentRegex\Options\ProtocolOption;
use Maestroerror\EloquentRegex\Options\CardTypeOption;
use Maestroerror\EloquentRegex\Options\OnlyAlphanumericOption;
use Maestroerror\EloquentRegex\Options\HtmlTagsOption;

/**
 * Class OptionsMapper
 *
 * Maps string identifiers (Used as methods for OptionsBuilder) to specific option class methods.
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
        "minDigits" => [NumberOption::class, "setMinValue"],
        "maxDigits" => [NumberOption::class, "setMaxValue"],
        "numberAmount" => [NumberOption::class, "setExactValue"],

        "onlyChars" => [CharacterOption::class, "allow"],
        "excludeChars" => [CharacterOption::class, "exclude"],
        "minUppercase" => [CharacterOption::class, "minUppercase"],
        "minLowercase" => [CharacterOption::class, "minLowercase"],
        
        "minSpecialChars" => [CharOption::class, "minSpecialCharacters"],
        "maxSpecialChars" => [CharOption::class, "maxSpecialCharacters"],
        "onlyLowercase" => [CharOption::class, "onlyLowercase"],
        "onlyUppercase" => [CharOption::class, "onlyUppercase"],
        "noSpecialChars" => [CharOption::class, "noSpecialCharacters"],
        
        "validIPv6" => [IPv6Option::class, "validIPv6"],

        "isFile" => [FileOption::class, "isFile"],
        "isDirectory" => [FileOption::class, "isDirectory"],
        
        "fileExists" => [FileExistsOption::class, "fileExists"],

        "specificCurrencies" => [SpecificCurrenciesOption::class, "setSpecificCurrencies"],
        "onlyUSD" => [SpecificCurrenciesOption::class, "onlyUSD"],
        "onlyEUR" => [SpecificCurrenciesOption::class, "onlyEUR"],
        "onlyGBP" => [SpecificCurrenciesOption::class, "onlyGBP"],
        "onlyGEL" => [SpecificCurrenciesOption::class, "onlyGEL"],

        "pathType" => [PathTypeOption::class, "setPathType"],

        "countryCode" => [CountryCodeOption::class, "setCountryCode"],

        "noSpaces" => [ContainSpacesOption::class, "noSpaces"],
        "noDoubleSpaces" => [ContainSpacesOption::class, "noDoubleSpaces"],
        "maxSpaces" => [ContainSpacesOption::class, "maxSpaces"],

        "onlyDomains" => [DomainSpecificOption::class, "setAllowedDomains"],
        "onlyExtensions" => [DomainSpecificOption::class, "setAllowedExtensions"],

        "onlyProtocol" => [ProtocolOption::class, "onlyProtocol"],
        "onlyHttp" => [ProtocolOption::class, "onlyHttp"],
        "onlyHttps" => [ProtocolOption::class, "onlyHttps"],

        "onlyVisa" => [CardTypeOption::class, "onlyVisa"],
        "onlyMasterCard" => [CardTypeOption::class, "onlyMasterCard"],
        "onlyAmex" => [CardTypeOption::class, "onlyAmex"],
        "cardTypes" => [CardTypeOption::class, "allowCardTypes"],

        "onlyAlphanumeric" => [OnlyAlphanumericOption::class, "onlyAlphanumeric"],

        "onlyTags" => [HtmlTagsOption::class, "allowTags"],
        "restrictTags" => [HtmlTagsOption::class, "restrictTags"],
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
}

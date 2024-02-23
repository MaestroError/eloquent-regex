<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;


class TextOrNumbersPattern extends BasePattern {

    use Pattern;

    protected string $pattern = "[a-zA-Z0-9]";
    
    public static string $name = "textOrNumbers";

    public static array $args = [
        "minLength", // First argument (should always be int or string based option)
        "maxLength", // Second argument
        "minUppercase", // Third argument and etc.
        "minLowercase",
        "minNumbers",
        "maxNumbers",
    ];

    public static array $defaultOptions = [
        // "maxLength" => 2
    ];


    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}+$/";
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}+/";
    }
}
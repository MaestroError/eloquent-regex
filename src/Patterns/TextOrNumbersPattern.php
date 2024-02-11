<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;


class TextOrNumbersPattern extends BasePattern {

    use Pattern;

    protected array $options = [];
    protected string $pattern = "[a-zA-Z0-9]";
    
    public static string $name = "textOrNumbers";

    public static array $args = [
        "minLength",
        "maxLength",
        "minUppercase",
        "minLowercase",
        "minNumbers",
        "maxNumbers",
    ];

    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}+$/";
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}+/";
    }
}
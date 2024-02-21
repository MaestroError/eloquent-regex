<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;


class PasswordPattern extends BasePattern {

    use Pattern;

    // Suspicous pattern, better to use for validation
    protected string $pattern = "[a-zA-Z0-9!@#$%^&*(),.?\":{}|<>]";
    
    public static string $name = "password";

    public static array $args = [
        "minLength",
        "minUppercase",
        "minNumbers",
        "minSpecialChars"
    ];

    
    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}+$/";
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}+/";
    }
}
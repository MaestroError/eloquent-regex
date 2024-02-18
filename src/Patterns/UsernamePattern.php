<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class UsernamePattern extends BasePattern {

    use Pattern;

    // Suspicous pattern, better to use for validation
    protected string $pattern = "\b[a-zA-Z0-9_-]{4,15}\b(?![!@#$%^&*().])";

    public static string $name = "username";

    public static array $args = [
        "maxLength"
    ];
}

<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class EmailPattern extends BasePattern {

    use Pattern;

    protected string $pattern = "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}";
    
    public static string $name = "email";

    public static array $args = [
        "maxLength",
    ];
}

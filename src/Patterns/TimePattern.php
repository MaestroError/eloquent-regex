<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class TimePattern extends BasePattern {

    use Pattern;

    protected string $pattern = "(?:[01]?[0-9]|2[0-3]):[0-5][0-9](?::[0-5][0-9])?(?:\s?(AM|PM))?";

    public static string $name = "time";

    // No additional arguments are expected for this pattern
    public static array $args = [];
}

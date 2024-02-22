<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class PhonePattern extends BasePattern {

    use Pattern;

    // For more precise validation use package: giggsey/libphonenumber-for-php
    protected string $pattern = "(?:[+\d]{1,4})[ -]?(?:[()\d]{1,5})[- \d]{4,23}";

    public static string $name = "phone";

    public static array $args = [
        "countryCode"
    ];
}

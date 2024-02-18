<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class DatePattern extends BasePattern {

    use Pattern;

    protected string $pattern = "(\\d{4}([-\/.])\\d{2}\\2\\d{2})|(\\d{2}([-\/.])\\d{2}\\4\\d{2,4})";

    public static string $name = "date";

    public static array $args = [];
}

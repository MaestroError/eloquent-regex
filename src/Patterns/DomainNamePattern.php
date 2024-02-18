<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class DomainNamePattern extends BasePattern {

    use Pattern;

    protected string $pattern = "([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.[a-zA-Z]{2,}";
    
    public static string $name = "domainName";

    // No specific arguments required for this pattern
    public static array $args = [];
}


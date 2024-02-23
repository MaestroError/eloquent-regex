<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class UrlPattern extends BasePattern {

    use Pattern;

    protected string $pattern = "(https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/[a-zA-Z0-9\/]*)?)";

    
    public static string $name = "url";

    public static array $args = [
        "onlyProtocol"
    ];

}

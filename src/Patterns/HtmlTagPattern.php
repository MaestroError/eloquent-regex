<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class HtmlTagPattern extends BasePattern {

    use Pattern;

    protected string $pattern = "<([A-Za-z][A-Za-z0-9]*)\b[^>]*>(.*?)<\/\\1>";
    
    public static string $name = "htmlTag";

    public static array $args = [
        "restrictTags",
        "onlyTags",
    ];
}

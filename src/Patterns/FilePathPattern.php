<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class FilePathPattern extends BasePattern {

    use Pattern;

    // Matches both Directory and File paths
    protected string $pattern = "[~\/]?[^\/:*,?\"<>|\r\n\s]+(?:\/[^\/:*,?\"<>|\r\n\s]+)+\/?(?:\.[a-zA-Z0-9]+)?";

    public static string $name = "filePath";

    // @todo add options
    public static array $args = [];
}

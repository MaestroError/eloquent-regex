<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class FilePathPattern extends BasePattern {

    use Pattern;

    // @todo add pattern For linux
    protected string $pattern = "(?:[A-Za-z]:[\\/]|\\|\/)?(?:[^\\/:*?\"<>|\r\n]+[\\/])*[^\\/:*?\"<>|\r\n]+\.(?:[a-zA-Z0-9]+)\b";

    public static string $name = "filePath";

    public static array $args = [];
}

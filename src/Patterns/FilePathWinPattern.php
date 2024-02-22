<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class FilePathWinPattern extends BasePattern {

    use Pattern;

    // Regex pattern for Windows file paths
    // Matches both Directory and File paths
    protected string $pattern = "(?!.*(?:\\\\\\\\|\/\/))(?:[A-Za-z]*)((?:[A-Za-z]:|.)?\\\\[^:*,?\"<>|\\r\\n]*)";

    public static string $name = "filePathWin";

    public static array $args = [
        "isDirectory",
        "isFile",
        "fileExists",
    ];
}

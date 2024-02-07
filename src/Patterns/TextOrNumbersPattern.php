<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;

class TextOrNumbersPattern extends BasePattern {
    protected array $options = [];
    protected string $pattern = "[a-zA-Z0-9]";
}
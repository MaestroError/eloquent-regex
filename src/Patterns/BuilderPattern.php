<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Patterns\BasePattern;

class BuilderPattern extends BasePattern {
    protected array $options = [];
    protected string $pattern = "";
}
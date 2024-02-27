<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderTraits;

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;

/**
 * trait BuilderPatternMethods
 * 
 * Used in Maestroerror\EloquentRegex\Builder class to enhance it with starting methods
 */
trait InitMethods {

    public function source(string $str): self {
        $this->setString($str);
        return $this;
    }
}

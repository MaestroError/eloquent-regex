<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;

/**
 * trait BuilderPatternMethods
 * 
 * Used in Maestroerror\EloquentRegex\Builder class to enhance it with BuilderPattern specific methods
 */
trait BuilderPatternMethods {

    public function start(): BuilderPattern {
        $this->pattern = new BuilderPattern($this);
        // Pass the current Builder instance to the BuilderPattern
        return $this->pattern;
    }

    public function pattern(callable $callback) {
        $this->pattern = new BuilderPattern($this);
        $builderPattern = $callback($this->pattern);
        return $builderPattern->end();
    }
}

<?php

namespace Maestroerror\EloquentRegex\Traits\Patterns;

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;

trait BuilderPatternTrait {

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

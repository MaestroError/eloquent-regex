<?php

namespace Maestroerror\EloquentRegex\Traits\Patterns;

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;

trait BuilderPatternTrait {

    public function start(): BuilderPattern {
        // Pass the current Builder instance to the BuilderPattern
        return new BuilderPattern($this);
    }

    public function pattern(callable $callback) {
        $builderPattern = new BuilderPattern($this);
        $builderPattern = $callback($builderPattern);
        return $builderPattern->end();
    }
}

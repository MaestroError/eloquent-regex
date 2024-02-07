<?php

namespace Maestroerror\EloquentRegex\Traits;

trait AddToPatternTrait {
    public function addToPattern(string $pattern): string {
        if (!method_exists($this, 'build')) {
            throw new \LogicException("The class using AddToPatternTrait must implement the build method.");
        }

        $builtPattern = $this->build();

        if (!is_string($builtPattern)) {
            throw new \UnexpectedValueException("The build method must return a string.");
        }

        return $pattern . $builtPattern;
    }
}

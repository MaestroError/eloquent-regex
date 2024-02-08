<?php

namespace Maestroerror\EloquentRegex\Traits\Patterns;

use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;

trait TextOrNumbersTrait {

    public function textOrNumbers(
        mixed $minLength = 1, 
        int $maxLength = 0, 
        int $minUppercase = 0, 
        int $minLowercase = 0, 
        int $minNumbers = 0, 
        int $maxNumbers = 0
    ): self {
        // Set needed pattern
        $this->pattern = new TextOrNumbersPattern();

        // Handle options array scenario
        if (is_array($minLength)) {
            $this->processConfigArray($minLength);
        }
        // Handle argument scenario
        else if (is_int($minLength)) {
            $values = func_get_args();
            $args = [
                "minLength",
                "maxLength",
                "minUppercase",
                "minLowercase",
                "minNumbers",
                "maxNumbers",
            ];
            $this->processArguments($args, $values, function($value) {
                return $value > 0;
            });
        }
        // Handle callback scenario
        else if (is_callable($minLength)) {
            $this->processCallback($minLength);
        } 
        else {
            throw new \InvalidArgumentException(__FUNCTION__ . " methods's first argument should be an int (minLength), array of options or a callback function");
        }

        return $this;
    }
}

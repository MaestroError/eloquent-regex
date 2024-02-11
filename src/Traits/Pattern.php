<?php

namespace Maestroerror\EloquentRegex\Traits;

trait Pattern {
    
    public static function execute(
        mixed $firstArgument = 1, 
        ...$args
    ): array {

        // Handle options array scenario
        if (is_array($firstArgument)) {
            return $firstArgument;
        }

        // Handle argument scenario
        if (is_int($firstArgument) || is_string($firstArgument)) {
            $values = func_get_args();
            $args = self::$args;
            $options = self::processArguments($args, $values, function($value) {
                return $value > 0;
            });
        }
        // Handle callback scenario
        else if (is_callable($firstArgument)) {
            $options = self::processCallback($firstArgument);
        } 
        else {
            throw new \InvalidArgumentException(__FUNCTION__ . " methods's first argument should be an int or string (firstArgument), array of options or a callback function");
        }

        return $options;
    }
}
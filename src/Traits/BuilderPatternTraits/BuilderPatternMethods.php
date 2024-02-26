<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;

/**
 * trait BuilderPatternMethods
 * 
 * Used in Maestroerror\EloquentRegex\Builder class to enhance it with BuilderPattern specific methods
 */
trait BuilderPatternMethods {

    /**
     * Initializes a new BuilderPattern instance.
     *
     * This method starts the construction of a regex pattern using the BuilderPattern class.
     * It sets the current pattern in the Builder class to a new instance of BuilderPattern,
     * allowing the user to chain methods to construct the desired regex pattern.
     *
     * @return BuilderPattern An instance of BuilderPattern for building complex regex patterns.
     */
    public function start(): BuilderPattern {
        // Pass the current Builder instance to the BuilderPattern
        $this->pattern = new BuilderPattern($this);
        return $this->pattern;
    }

    /**
     * Creates a custom regex pattern using a callback function.
     *
     * This method allows the user to define a custom regex pattern using the BuilderPattern class.
     * If a callback is provided, it is executed with the BuilderPattern instance as an argument,
     * allowing the user to define the pattern using method chaining. If no callback is provided,
     * it simply initiates a new BuilderPattern instance.
     *
     * @param callable|null $callback A callback function that receives a BuilderPattern instance to define the regex pattern.
     * @return BuilderContract|BuilderPattern Returns the main Builder instance after the pattern is defined.
     */
    public function pattern(callable|null $callback): BuilderContract|BuilderPattern {
        if (is_null($callback)) {
            return $this->start();
        }
        // Pass the current Builder instance to the BuilderPattern
        $this->pattern = new BuilderPattern($this);
        // Run callback to create pattern
        $builderPattern = $callback($this->pattern);
        // return back the Builder object
        return $builderPattern->end();
    }
}

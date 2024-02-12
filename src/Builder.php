<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\OptionsManager;
use Maestroerror\EloquentRegex\OptionsBuilder;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\BuilderPatternMethods;
use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;

class Builder implements BuilderContract {

    use BuilderPatternMethods;

    protected string $str;
    protected PatternContract $pattern;
    protected OptionsManager $manager;

    public array $patterns = 
    [
        TextOrNumbersPattern::class
    ];

    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
        // @todo add patterns via config
    }

    protected function processArguments(array $args, array $values, callable $condition): void {
        $options = [];
        // Build options array based on condition
        for ($i=0; $i < count($args); $i++) { 
            if (isset($values[$i])) {
                // Use the callable $condition to determine if the option should be set
                if ($condition($values[$i])) {
                    $options[$args[$i]] = $values[$i];
                }
            }
        }
        
        $this->processConfigArray($options);
    }

    protected function processConfigArray(array $options) {
        // Build options in options manager
        $this->manager->buildOptions($options);
        // Set used options to pattern
        $this->pattern->setOptions($this->manager->getUsedOptions());
    }

    protected function processCallback(callable $callback) {
        $optionsBuilder = new OptionsBuilder();
        $optionsBuilder = $callback($optionsBuilder);
        $this->processConfigArray($optionsBuilder->getOptions());
    }

    protected function validateAsInput(): bool {
        if (!$this->pattern->validateInput($this->str)) {
            return false;
        }
        return true;
    }

    protected function validateAsString(): bool {
        if (!$this->pattern->validateMatches($this->str)) {
            return false;
        }
        return true;
    }

    protected function getAllMatches(): array {
        return $this->pattern->getMatches($this->str);
    }

    /**
     * Checks if a pattern is set.
     *
     * @return bool Returns true if a pattern is set, false otherwise.
     */
    protected function patternIsSet(): bool {
        return isset($this->pattern) && !empty($this->pattern);
    }
    
    /* Public methods (API) */

    public function setString(string $str): void {
        $this->str = $str;
    }

    public function get(): array {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before getting matches.");
        }
        return $this->getAllMatches();
    }
    
    public function check(): bool {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before performing check.");
        }
        return $this->validateAsInput();
    }
    
    public function checkString(): bool {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before performing string check.");
        }
        return $this->validateAsString();
    }

    public function count(): int {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before counting matches.");
        }
        return count($this->getAllMatches());
    }
    
    public function toRegex(): string {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before converting to regex.");
        }
        return $this->pattern->getPattern();
    }

    public function setOptions(array|callable $config): void {
        // Check if the pattern is set
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before setting options.");
        }
        // Handle options array scenario
        if (is_array($config)) {
            $this->processConfigArray($config);
        }
        // Handle callback scenario
        else if (is_callable($config)) {
            $this->processCallback($config);
        } 
    }
    
    public function registerPattern(PatternContract $pattern): self {
        $this->patterns[] = $pattern;
        return $this;
    }
    
    public function registerPatterns(array $patterns): self {
        foreach ($patterns as $pattern) {
            if (is_subclass_of($pattern, PatternContract::class)) {
                $this->registerPattern(new $pattern());
            } else {
                throw new \InvalidArgumentException("Class " . get_class($pattern) . " must implement PatternContract");
            }
        }
        return $this;
    }
    
    public function getPatterns(): array {
        return $this->patterns;
    }
    
    public function __call(
        $name,
        $args
    ): self {
        if (empty($this->patterns)) {
            throw new \LogicException("No patterns are registered in Builder.");
        }

        foreach ($this->patterns as $pattern) {
            if ($pattern::$name == $name) {
                // Set needed pattern
                $this->pattern = new $pattern();

                $options = $pattern::execute(...$args);
                $this->processConfigArray($options);

                return $this;
            }
        }

        throw new \LogicException("Pattern with name $name not found.");
    }

}

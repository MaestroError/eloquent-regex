<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\OptionsManager;
use Maestroerror\EloquentRegex\OptionsBuilder;
use Maestroerror\EloquentRegex\Traits\Patterns\TextOrNumbersTrait;

class Builder {

    use TextOrNumbersTrait;

    protected string $str;
    protected PatternContract $pattern;
    protected OptionsManager $manager;
    protected string $regex = "";

    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
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

    protected function countMatches(): int {
        $count = $this->pattern->countMatches($this->str);
        return $count;
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
        return $this->countMatches();
    }
    
    public function toRegex(): string {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before converting to regex.");
        }
        return $this->pattern->getPattern();
    }

    public function setOptions(array|callable $config) {
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

}

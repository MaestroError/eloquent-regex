<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;
use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\OptionsManager;
use Maestroerror\EloquentRegex\OptionsBuilder;

class Builder {
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
        print_r($options);
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
    
    /* Public methods (API) */

    public function setTargetString(string $str): void {
        $this->str = $str;
    }

    public function get(): array {
        return $this->getAllMatches();
    }

    public function check(): string {
        return $this->validateAsInput();
    }

    public function checkString(): string {
        return $this->validateAsString();
    }

    public function count(): int {
        return count($this->getAllMatches());
    }

    public function toRegex(): string {
        return $this->pattern->getPattern();
    }

    /* TextAndNumbersPattern implementation */
    public function textOrNumbers(int|array|callable $minLength, int $maxLength = 0, int $minUppercase = 0, int $minLowercase = 0, int $minNumbers = 0, int $maxNumbers = 0): self {
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
            
        }

        return $this;
    }

}

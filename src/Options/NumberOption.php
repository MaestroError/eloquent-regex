<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class NumberOption implements OptionContract {

    private $minValue = null;
    private $maxValue = null;
    private $exactValue = null;

    public function validate(string $input): bool {

        $numericCount = preg_match_all('/\d/', $input);

        if ($this->exactValue !== null && $numericCount !== $this->exactValue) {
            return false;
        }

        if ($this->minValue !== null && $numericCount < $this->minValue) {
            return false;
        }

        if ($this->maxValue !== null && $numericCount > $this->maxValue) {
            return false;
        }

        return true;
    }

    public function build(): string {
        // Check if exactValue is set
        if ($this->exactValue !== null) {
            return "\\d{{$this->exactValue}}";
        }
    
        // Build the pattern based on minValue and maxValue
        $min = $this->minValue ?? '';
        $max = $this->maxValue ?? '';
    
        if ($min === '' && $max === '') {
            // If both min and max are not set, default to '\d+'
            $pattern = "\\d+";
        } else {
            // Handle cases where min and/or max are set
            $pattern = "\\d{{$min},{$max}}";
        }

        // Handling for only minValue or maxValue set
        if ($min === '' && $max !== '') {
            $pattern = "\\d{0,{$max}}";  // Use {0, max} instead of {, max}
        }
    
        return $pattern;
    }

    // Option methods
    public function setMinValue(int $minValue) {
        $this->minValue = $minValue;
        $this->exactValue = null; // Reset exact value if min or max value is set
        return $this;
    }

    public function setMaxValue(int $maxValue) {
        $this->maxValue = $maxValue;
        $this->exactValue = null; // Reset exact value if min or max value is set
        return $this;
    }

    public function setExactValue(int $exactValue) {
        $this->exactValue = $exactValue;
        $this->minValue = null;
        $this->maxValue = null;
        return $this;
    }
}

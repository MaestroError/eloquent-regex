<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;

class TextOrNumbersPattern implements PatternContract {
    private array $options;

    public function __construct() {
        $this->options = [];
    }

    public function build(): string {
        // Default pattern to match text and numbers
        $pattern = '[a-zA-Z0-9]';

        // Modify the pattern based on options if they exist
        foreach ($this->options as $option) {
            $pattern .= $option->build();
        }

        return $pattern;
    }

    public function addToPattern(string $existingPattern): string {
        return $existingPattern . $this->build();
    }

    public function reset() {
        $this->options = [];
    }

    public function description(): string {
        return 'Pattern to match a combination of text and numbers.';
    }

    public function setOptions(array $options) {
        $this->options = $options;
    }

    public function setOption(OptionContract $options) {
        $this->options[] = $options;
    }

    public function validateInput(string $input): bool {
        foreach ($this->options as $option) {
            if (!$option->validate($input)) {
                return false;
            }
        }

        // Default validation if no specific options are set
        return preg_match("/^" . $this->build() . "$/", $input) === 1;
    }
}

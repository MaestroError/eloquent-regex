<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;

class TextOrNumbersPattern implements PatternContract {

    private array $options;
    private string $pattern = "[a-zA-Z0-9]";

    public function __construct() {
        $this->options = [];
    }

    public function getPattern(): string {
        $pattern = $this->pattern;
        return $pattern;
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

    private function validateOptions(string $input): bool {
        foreach ($this->options as $option) {
            if (!$option->validate($input)) {
                return false;
            }
        }
        return true;
    }

    private function filterByOptions(array $allMatches): array {
        $filteredMatches = array_filter($allMatches, function($match) {
            if(!$this->validateOptions($match)) {
                return false;
            }
            return true;
        });
        return array_values($filteredMatches);
    }

    public function validateInput(string $input): bool {
        // Validate against the main pattern
        $mainPattern = "/^{$this->pattern}+$/";

        if (!preg_match($mainPattern, $input)) {
            return false;
        }

        if(!$this->validateOptions($input)) {
            return false;
        }

        return true;
    }

    public function validateMatches(string $input): bool {
        // Validate against the main pattern
        $mainPattern = "/{$this->pattern}+/";

        if (preg_match_all($mainPattern, $input, $matches) == 0) {
            return false;
        }

        $allMatches = $matches[0];

        $filteredMatches = $this->filterByOptions($allMatches);

        return count($filteredMatches) > 0;
    }

    public function getMatches(string $input): array {
        $mainPattern = "/{$this->pattern}+/";
        preg_match_all($mainPattern, $input, $matches);
        $allMatches = $matches[0];

        // Filter matches based on each option
        $filteredMatches = $this->filterByOptions($allMatches);
        return $filteredMatches;
    }
}

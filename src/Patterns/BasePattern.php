<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;

class BasePattern implements PatternContract {

    protected array $options = [];
    protected string $pattern = "[a-z]";

    /* API */

    public function getPattern(): string {
        $pattern = $this->pattern;
        return $pattern;
    }

    public function reset() {
        $this->options = [];
    }

    public function setOptions(array $options) {
        $this->options = $options;
    }

    public function setOption(OptionContract $options) {
        $this->options[] = $options;
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

    /* Helper methods */

    protected function filterByOptions(array $allMatches): array {
        $filteredMatches = array_filter($allMatches, function($match) {
            if(!$this->validateOptions($match)) {
                return false;
            }
            return true;
        });
        return array_values($filteredMatches);
    }
    

    protected function validateOptions(string $input): bool {
        foreach ($this->options as $option) {
            if (!$option->validate($input)) {
                return false;
            }
        }
        return true;
    }
}

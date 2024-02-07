<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;

/**
 * Class BasePattern
 * 
 * Serves as a base class for regex patterns. Provides a basic structure and functionality
 * common to various types of regex patterns.
 */
class BasePattern implements PatternContract {

    /**
     * @var array List of options applied to the pattern.
     */
    protected array $options = [];

    /**
     * @var string The base regex pattern.
     */
    protected string $pattern = "[a-z]";

    /**
     * Retrieves the current regex pattern.
     *
     * @return string The current regex pattern.
     */
    public function getPattern(): string {
        return $this->pattern;
    }

    /**
     * Resets the pattern to its initial state.
     */
    public function reset() {
        $this->options = [];
    }

    /**
     * Sets the options for this pattern.
     *
     * @param array $options Array of options to be applied to the pattern.
     */
    public function setOptions(array $options) {
        $this->options = $options;
    }

    /**
     * Adds an option to this pattern.
     *
     * @param OptionContract $option Option to be added.
     */
    public function setOption(OptionContract $option) {
        $this->options[] = $option;
    }

    /**
     * Validates an input string against the pattern and its options.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input string validates against the pattern and options, false otherwise.
     */
    public function validateInput(string $input): bool {
        // Validate against the main pattern
        $mainPattern = "/^{$this->pattern}+$/";

        // First, check if the entire input matches the main pattern
        if (!preg_match($mainPattern, $input)) {
            return false;
        }

        // Then, validate the input against each option
        return $this->validateOptions($input);
    }

    /**
     * Validates that the input string contains matches for the pattern, filtered by options.
     *
     * @param string $input The input string to validate.
     * @return bool True if there are any matches for the pattern in the input, after applying options.
     */
    public function validateMatches(string $input): bool {
        // Validate against the main pattern
        $mainPattern = "/{$this->pattern}+/";

        // Find all matches for the main pattern in the input
        if (preg_match_all($mainPattern, $input, $matches) == 0) {
            return false;
        }

        // Filter these matches based on the options
        $filteredMatches = $this->filterByOptions($matches[0]);

        // Check if there are any matches left after filtering
        return count($filteredMatches) > 0;
    }

    /**
     * Retrieves all matches of the pattern in the input string, filtered by options.
     *
     * @param string $input The input string to search for matches.
     * @return array An array of matches.
     */
    public function getMatches(string $input): array {
        $mainPattern = "/{$this->pattern}+/";
        preg_match_all($mainPattern, $input, $matches);

        // Filter matches based on each option
        return $this->filterByOptions($matches[0]);
    }

    /**
     * Filters an array of matches based on the options.
     *
     * @param array $allMatches Array of matches to be filtered.
     * @return array Filtered array of matches.
     */
    protected function filterByOptions(array $allMatches): array {
        // Use array_filter to keep only those matches that pass all options' validation
        return array_values(array_filter($allMatches, function($match) {
            return $this->validateOptions($match);
        }));
    }
    
    /**
     * Validates an input string against all set options.
     *
     * @param string $input The input string to validate against the options.
     * @return bool True if the input string passes all options' validation, false otherwise.
     */
    protected function validateOptions(string $input): bool {
        foreach ($this->options as $option) {
            if (!$option->validate($input)) {
                return false;
            }
        }
        return true;
    }
}
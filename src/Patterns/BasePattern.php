<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\OptionsBuilder;
use Maestroerror\EloquentRegex\Traits\Pattern;

/**
 * Class BasePattern
 * 
 * Serves as a base class for regex patterns. Provides a basic structure and functionality
 * common to various types of regex patterns.
 */
class BasePattern implements PatternContract {

    use Pattern;

    /**
     * @var array List of options applied to the pattern.
     */
    protected array $options = [];

    /**
     * @var string The base regex pattern.
     */
    protected string $pattern = "[a-z]";

    /**
     * @var string Static property to define the pattern name.
     */
    public static string $name = "";

    /**
     * @var array Static property to define the pattern arguments one by one.
     */
    public static array $args = [];

    /**
     * @var array Static property to apply the default options for the pattern.
     */
    public static array $defaultOptions = [];

    /**
     * @var string A string to hold expression flags for the regex pattern.
     */
    protected string $expressionFlags = "";

    /**
     * Retrieves the current regex pattern.
     *
     * @return string The current regex pattern.
     */
    public function getPattern(): string {
        return $this->pattern;
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
     * Validates an input string against the pattern and its options as exact match.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input string validates against the pattern and options, false otherwise.
     */
    public function validateInput(string $input): bool {
        // Get the main pattern
        $mainPattern = $this->getInputValidationPattern();
        
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
        // Get the main pattern for matches
        $mainPattern = $this->getMatchesValidationPattern();

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
    public function getMatches(string $input, bool $returnGroups = false): ?array {
        $mainPattern = $this->getMatchesValidationPattern();
        preg_match_all($mainPattern, $input, $matches);

        if (!$matches[0]) {
            return null;
        }

        if ($returnGroups) {
            // Filter matches but keep indexes same
            $results = $this->filterByOptions($matches[0], false);
            // Unset matches and keep only groups
            unset($matches[0]);
            $groups = $matches;
            return [
                "results" => $results,
                "groups" => $groups
            ];
        } else {
            // Filter matches based on each option
            return $this->filterByOptions($matches[0]);
        }
    }

    /**
     * Filters an array of matches based on the options.
     *
     * @param array $allMatches Array of matches to be filtered.
     * @return array Filtered array of matches.
     */
    protected function filterByOptions(array $allMatches, $fixArrayIndexes = true): array {
        // Use array_filter to keep only those matches that pass all options' validation
        $filtered = array_filter($allMatches, function($match) {
            return $this->validateOptions($match);
        });

        if ($fixArrayIndexes) {
            return array_values($filtered);
        } else {
            return $filtered;
        }
    }
    
    /**
     * Validates an input string against all set options.
     *
     * @param string $input The input string to validate against the options.
     * @return bool True if the input string passes all options' validation, false otherwise.
     */
    protected function validateOptions(string $input): bool {
        if (!empty($this->options)) {
            foreach ($this->options as $option) {
                if (!$option->validate($input)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Default implementation of generating regex patterns for input validation
     *
     * @return string The regex pattern for validating the entire input.
     */
    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}$/" . $this->expressionFlags;
    }

    /**
     * Default implementation of generating regex patterns for matches validation
     *
     * @return string The regex pattern for finding matches within the input.
     */
    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}/" . $this->expressionFlags;
    }
    
    /**
     * Processes an array of arguments and builds an options array.
     *
     * @param array $args Names of the arguments.
     * @param array $values Values of the arguments.
     * @return array An associative array of options.
     */
    protected static function processArguments(array $args, array $values): array {
        $options = [];
        // Build options array based on condition
        for ($i=0; $i < count($args); $i++) { 
            if (isset($values[$i])) {
                // If value is true (so not "", 0, null)
                if ($values[$i]) {
                    $options[$args[$i]] = $values[$i];
                }
            }
        }
        
        return $options;
    }

    /**
     * Processes a callback function to configure options.
     *
     * @param callable $callback The callback function used for configuring options.
     * @return array An associative array of options set by the callback.
     */
    protected static function processCallback(callable $callback): array {
        $optionsBuilder = new OptionsBuilder();
        $callback($optionsBuilder);
        return $optionsBuilder->getOptions();
    }

    /**
     * Adds a regex expression flag to the pattern.
     *
     * @param string $flag The single-character flag to add to the regex pattern.
     */
    public function addExpressionFlag(string $flag): void {
        if (strpos($this->expressionFlags, $flag) === false) {
            $this->expressionFlags .= $flag;
        }
    }
}

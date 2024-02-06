<?php

namespace Maestroerror\EloquentRegex\Contracts;

/**
 * Interface PatternContract
 *
 * Defines the structure for regex pattern classes within the EloquentRegex system.
 * Each pattern class implementing this interface is responsible for building and managing
 * a specific segment of a regex pattern.
 */
interface PatternContract {

    /**
     * Builds and returns the regex pattern segment for this pattern.
     *
     * @return string The constructed regex pattern segment.
     */
    public function build(): string;

    /**
     * Adds the built pattern segment to an existing regex pattern.
     *
     * @param string $existingPattern The existing regex pattern to which this pattern segment will be added.
     * @return string The updated regex pattern including this pattern segment.
     */
    public function addToPattern(string $existingPattern): string;

    /**
     * Resets the pattern to its initial state.
     *
     * Useful for reusing the pattern object for building new patterns.
     *
     * @return void
     */
    public function reset();

    /**
     * Provides a textual description of this pattern.
     *
     * Can be used for documentation or debugging purposes.
     *
     * @return string A textual description of the pattern.
     */
    public function description(): string;

    /**
     * Sets the options for this pattern.
     *
     * @param OptionContract $option The options to be applied to this pattern.
     * @return void
     */
    public function setOption(OptionContract $option);

    /**
     * Sets the options for this pattern.
     *
     * @param array $options The options to be applied to this pattern.
     * @return void
     */
    public function setOptions(array $options);

    /**
     * Validates a given input string against this pattern.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input is valid according to this pattern, false otherwise.
     */
    public function validateInput(string $input): bool;
}
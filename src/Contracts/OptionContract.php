<?php

namespace Maestroerror\EloquentRegex\Contracts;

/**
 * Interface for defining regex options.
 *
 * This interface provides a set of methods for building and managing
 * regex patterns in a flexible and modular way. Implementing classes
 * can define specific regex behaviors and characteristics.
 */
interface OptionContract {
    /**
     * Validates the given input against the option's criteria.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input is valid, false otherwise.
     */
    public function validate(string $input);

    /**
     * Builds and returns the regex pattern for this option.
     *
     * @return string The constructed regex pattern segment.
     */
    public function build(): string;

    /**
     * Resets the option to its initial state.
     *
     * This method is useful for reusing the option object for building
     * new patterns.
     *
     * @return self For fluent interface.
     */
    public function reset();

    /**
     * Marks this option as optional in the regex pattern.
     *
     * When marked as optional, the pattern segment defined by this option
     * is not required for a successful match.
     *
     * @return self For fluent interface.
     */
    public function optional();

    /**
     * Provides a description of this option.
     *
     * This method can be used for generating documentation or debugging.
     *
     * @return string A textual description of the option.
     */
    public function description(): string;

    /**
     * Adds this option's pattern to the given regex pattern.
     *
     * @param string $pattern The existing regex pattern to append to.
     * @return string The updated regex pattern including this option.
     */
    public function addToPattern(string $pattern): string;
}

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
     * Validates the given input against the option's criteria
     * Using PHP statements and/or built Regex pattern.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input is valid, false otherwise.
     */
    public function validate(string $input): bool;

    /**
     * Builds and returns the regex pattern for this option.
     *
     * @return string The constructed regex pattern segment.
     */
    public function build(): string;
}

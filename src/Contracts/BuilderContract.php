<?php

namespace Maestroerror\EloquentRegex\Contracts;

/**
 * Interface BuilderContract
 * 
 * Defines the public API for the Builder class in the EloquentRegex system.
 * This interface ensures a consistent structure for regex building operations.
 */
interface BuilderContract {

    /**
     * Sets the target string for regex operations.
     *
     * @param string $str The string to be analyzed or manipulated.
     * @return void
     */
    public function setString(string $str): void;

    /**
     * Retrieves all matches of the set pattern within the target string.
     *
     * @return array An array of all matches found.
     */
    public function get(): ?array;

    /**
     * Checks if the entire target string exactly matches the set pattern.
     *
     * @return bool True if the target string matches the pattern, false otherwise.
     */
    public function check(): bool;

    /**
     * Checks if the target string contains any matches for the set pattern.
     *
     * @return bool True if the target string contains matches, false otherwise.
     */
    public function checkString(): bool;

    /**
     * Counts the number of matches of the set pattern within the target string.
     *
     * @return int The number of matches found.
     */
    public function count(): int;

    /**
     * Converts the set pattern to its regex string representation.
     *
     * @return string The regex pattern as a string.
     */
    public function toRegex(): string;

    /**
     * Sets options for the pattern based on an array or a callback function.
     *
     * @param array|callable $config An array of options (optionName => value (arg)) or a callback function to configure options.
     * @return void
     */
    public function setOptions(array|callable $config): void;

    /**
     * Registers a single pattern in the Builder.
     *
     * @param PatternContract $pattern The pattern instance to be registered.
     * @return self Returns the Builder instance for fluent interface.
     */
    public function registerPattern(PatternContract $pattern): self;

    /**
     * Registers multiple patterns in the Builder.
     *
     * @param array $patterns An array of pattern instances to be registered.
     * @return self Returns the Builder instance for fluent interface.
     */
    public function registerPatterns(array $patterns): self;

    /**
     * Retrieves all registered patterns in the Builder.
     *
     * @return array An array of registered pattern instances.
     */
    public function getPatterns(): array;

    /**
     * Magic method to handle dynamic method calls.
     *
     * This method is triggered when invoking inaccessible or non-existing methods.
     * It is used to dynamically handle pattern-specific method calls.
     *
     * @param string $name The name of the method being called.
     * @param array $args An array of arguments passed to the method.
     * @return self Returns the Builder instance for fluent interface.
     */
    public function __call($name, $args): self;

}

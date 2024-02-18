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
     * Builds and returns the regex pattern segment for this pattern. (Without options)
     *
     * @return string The constructed regex pattern segment.
     */
    public function getPattern(): string;

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
     * @param array $options The options [OptionContract] to be applied to this pattern.
     * @return void
     */
    public function setOptions(array $options);

    /**
     * Validates a given input string against this pattern as exact match.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input is valid according to this pattern, false otherwise.
     */
    public function validateInput(string $input): bool;

    /**
     * Validates a given input string against this pattern as it includes min 1 match.
     *
     * @param string $input The input string to validate.
     * @return bool True if the input is valid according to this pattern, false otherwise.
     */
    public function validateMatches(string $input): bool;

    /**
     * Returns all matches found by this pattern.
     *
     * @param string $input The input string to validate.
     * @return array all matches found in input.
     */
    public function getMatches(string $input): ?array;

    /**
     * Generates the regex pattern for input validation.
     *
     * This pattern is used to check if the entire input string exactly matches the constructed pattern.
     *
     * @return string The regex pattern for validating the entire input.
     */
    public function getInputValidationPattern(): string;

    /**
     * Generates the regex pattern for finding matches within the input.
     *
     * This pattern is used to search for occurrences of the pattern within the input string.
     *
     * @return string The regex pattern for finding matches within the input.
     */
    public function getMatchesValidationPattern(): string;

    /**
     * Executes the pattern with the provided arguments.
     *
     * This method is responsible for processing the arguments provided to the pattern
     * and configuring it accordingly. It can handle an array of options, a set of
     * individual arguments, or a callback function for more complex configurations.
     *
     * @param mixed $firstArgument The first argument which could be an array of options,
     *                             an integer, a string, or a callback function.
     * @param mixed ...$args Additional arguments if the first argument is not an array or callback.
     * @return array Returns an array of configuration options processed by the method.
     * @throws \InvalidArgumentException If the first argument does not meet the expected types.
     */
    public static function execute(mixed $firstArgument = 1, ...$args): array;

}
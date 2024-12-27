<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\OptionsManager;
use Maestroerror\EloquentRegex\OptionsBuilder;
use Maestroerror\EloquentRegex\Traits\BuilderTraits\BuilderPatternMethods;
use Maestroerror\EloquentRegex\Traits\BuilderTraits\InitMethods;
use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;
use Maestroerror\EloquentRegex\Patterns\EmailPattern;
use Maestroerror\EloquentRegex\Patterns\UrlPattern;
use Maestroerror\EloquentRegex\Patterns\DomainNamePattern;
use Maestroerror\EloquentRegex\Patterns\DatePattern;
use Maestroerror\EloquentRegex\Patterns\TimePattern;
use Maestroerror\EloquentRegex\Patterns\IPAddressPattern;
use Maestroerror\EloquentRegex\Patterns\IPv6AddressPattern;
use Maestroerror\EloquentRegex\Patterns\CreditCardNumberPattern;
use Maestroerror\EloquentRegex\Patterns\PhonePattern;
use Maestroerror\EloquentRegex\Patterns\UsernamePattern;
use Maestroerror\EloquentRegex\Patterns\PasswordPattern;
use Maestroerror\EloquentRegex\Patterns\HtmlTagPattern;
use Maestroerror\EloquentRegex\Patterns\CurrencyPattern;
use Maestroerror\EloquentRegex\Patterns\FilePathPattern;
use Maestroerror\EloquentRegex\Patterns\FilePathWinPattern;

class Builder implements BuilderContract {

    use BuilderPatternMethods, InitMethods;

    /**
     * The string to be processed with regex.
     */
    protected string $str;

    /**
     * The current pattern being applied.
     */
    protected PatternContract $pattern;

    /**
     * Manages options for the current pattern.
     */
    protected OptionsManager $manager;

    /**
     * List of available patterns.
     */
    public array $patterns = 
    [
        TextOrNumbersPattern::class,
        EmailPattern::class,
        UrlPattern::class,
        DomainNamePattern::class,
        DatePattern::class,
        TimePattern::class,
        IPAddressPattern::class,
        IPv6AddressPattern::class,
        CreditCardNumberPattern::class,
        PhonePattern::class,
        UsernamePattern::class,
        PasswordPattern::class,
        HtmlTagPattern::class,
        CurrencyPattern::class,
        FilePathPattern::class,
        FilePathWinPattern::class,
    ];

    protected bool $returnGroups = false;
    protected bool $namedGroups = false;

    /**
     * Constructs a new Builder instance.
     *
     * @param string $str The initial string to process with regex.
     */
    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
        // @todo add patterns via config
    }

    /**
     * Processes an options array and applies it to the current pattern.
     *
     * @param array $options An associative array of options.
     */
    protected function processConfigArray(array $options) {
        // Build options in options manager
        $this->manager->buildOptions($options);
        // Set used options to pattern
        $this->pattern->setOptions($this->manager->getUsedOptions());
    }

    /**
     * Processes a callback function to configure options.
     *
     * @param callable $callback A function that configures options using an OptionsBuilder.
     */
    protected function processCallback(callable $callback) {
        $optionsBuilder = new OptionsBuilder();
        $callback($optionsBuilder);
        $this->processConfigArray($optionsBuilder->getOptions());
    }

    /**
     * Validates the current string as an exact match against the current pattern.
     *
     * @return bool True if the string is an exact match, false otherwise.
     */
    protected function validateAsInput(): bool {
        if (!$this->pattern->validateInput($this->str)) {
            return false;
        }
        return true;
    }

    /**
     * Validates if the current string contains any matches for the current pattern.
     *
     * @return bool True if the string contains matches, false otherwise.
     */
    protected function validateAsString(): bool {
        if (!$this->pattern->validateMatches($this->str)) {
            return false;
        }
        return true;
    }

    /**
     * Retrieves all matches of the current pattern within the string.
     *
     * @return array|null An array of matches or null if no matches are found.
     */
    protected function getAllMatches(): ?array {
        if ($this->getReturnGroups()) {
            // Get unfiltered matches and groups
            $resultsArray = $this->pattern->getMatches($this->str, true);
            if ($resultsArray["results"]) {
                // Get array of associative arrays with "result" and "groups" keys
                $groupedResults = $this->buildResultByGroup($resultsArray["results"], $resultsArray["groups"]);
                return $groupedResults;
            } else {
                return null;
            }
        } else {
            return $this->pattern->getMatches($this->str);
        }
    }

    /**
     * Build results array from filtered matches and groups.
     *
     * @param $matches filtered array from "preg_match_all", but with original indexes.
     * @param $groups array of captured groups from "preg_match_all".
     * @return array An array of matches and their captured groups.
     */
    protected function buildResultByGroup(array $matches, array $groups): array {
        // Empty array for grouped result
        $groupedResults = [];
        // Loop over only matches
        foreach ($matches as $index => $value) {
            // Add match as result
            $matchArray = [
                "result" => $value,
            ];
            // Use match index to get it's groups
            $capturedGroupsForThisMatch = [];
            foreach ($groups as $key => $groupArray) {
                if ($this->getNamedGroups()) {
                    if (is_string($key)) {
                        $capturedGroupsForThisMatch[$key] = $groupArray[$index];
                    }
                } else {
                    $capturedGroupsForThisMatch[] = $groupArray[$index];
                }
            }
            // Add captured groups under "groups" key
            $matchArray["groups"] = $capturedGroupsForThisMatch;
            // Add array to result
            $groupedResults[] = $matchArray;
        }
        return $groupedResults;
    }

    /**
     * Checks if a pattern is set.
     *
     * @return bool Returns true if a pattern is set, false otherwise.
     */
    protected function patternIsSet(): bool {
        return isset($this->pattern) && !empty($this->pattern);
    }

    /**
     * Removes duplicate values from an array of matches.
     *
     * @return array without duplicate values.
     */
    protected function removeDuplicates(): array {
        return array_unique($this->getAllMatches());
    }

    /**
     * Searches for a keyword in the multiline string.
     * 
     * @param string $keyword The keyword to search for.
     * @return array An array of matches or null if no matches are found.
     */
    protected function searchByKeyword(string $keyword): ?array {
        $builder = new Self($this->str);
        $builder->start()
            ->anyChars()
            ->lookAhead(function ($pattern) use ($keyword) {
                $pattern->exact($keyword);
            })->anyChars()->end(); // .+(?=$keyword).+

        return $builder->get();
    }

    /**
     * Searches for a pattern in the multiline string.
     * 
     * @param callable $callback The pattern to search for.
     * @return array An array of matches or null if no matches are found.
     */
    protected function searchBySubpattern(callable $callback): ?array {
        // Get pattern from callback
        $subPattern = new self($this->str);
        $callback($subPattern);

        $builder = new Self($this->str);
        $builder->start()
            ->anyChars()
            ->lookAhead(function ($pattern) use ($subPattern) {
                $pattern->addRawRegex($subPattern->toRegex());
            })->anyChars()->end();

        return $builder->get();
    }

    
    /**
     * Filters the current multiline string by the given keyword
     * And return every line except that includes keyword
     *
     * @param string $keyword The keyword to filter the string by.
     * @return array|null The filtered result as an array, or null if no match is found.
     */
    protected function exceptByKeyword(string $keyword): ?array {
        $builder = new Self($this->str);
        $builder->start()
            ->useStringBeginning()
            ->negativeLookAhead(function ($pattern) use ($keyword) {
                $pattern->anyChars()->exact($keyword);
            })->anyChars()->useStringEnd()->end()->asMultiline();

        return $builder->get();
    }
    
    /**
     * Filters the current multiline string by the given subpattern
     * And return every line except that includes subpattern
     *
     * @param callable $callback A callback function that defines the subpattern.
     * @return array|null The resulting pattern as an array, or null if no match is found.
     */
    protected function exceptBySubpattern(callable $callback): ?array {
        // Get pattern from callback
        $subPattern = new self($this->str);
        $callback($subPattern);

        $builder = new Self($this->str);
        $builder->start()
            ->useStringBeginning()
            ->negativeLookAhead(function ($pattern) use ($subPattern) {
                $pattern->anyChars()->addRawRegex($subPattern->toRegex());
            })->anyChars()->useStringEnd()->end()->asMultiline();

        return $builder->get();
    }

    protected function swapByCallback(callable $callback, array $results): array {
        $swapped = [];
        foreach ($results as $match) {
            $data = $match['groups'];
            $replaced = $callback($data);
            $swapped[] = $replaced;
        }
        return $swapped;
    }

    protected function swapByString(string $objectString, array $results): array {
        $swapped = [];
        foreach ($results as $match) {
            $data = $match['groups'];
            $swappedString = $objectString;
            foreach ($data as $key => $value) {
                $pattern = "/\[\s*" . preg_quote($key, '/') . "\s*\]/i";
                $swappedString = preg_replace($pattern, $value, $swappedString);
            }
            $swapped[] = $swappedString;
        }
        return $swapped;
    }

    protected function returnArrayOrCollection(?array $matches): mixed {
        // Check if Laravel Collection class exists and the collect helper function is available
        if (class_exists(\Illuminate\Support\Collection::class) && function_exists('collect')) {
            // Return matches as a Laravel Collection
            return collect($matches);
        }
    
        // Return matches as an array if Collection or collect() is not available
        return $matches;
    }
    
    /* Public methods (API) */

    public function setString(string $str): void {
        $this->str = $str;
    }

    // Actions start

    public function get(): mixed {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before getting matches.");
        }
    
        $matches = $this->getAllMatches();
    
        return $this->returnArrayOrCollection($matches);
    }
    
    public function check(): bool {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before performing check.");
        }
        return $this->validateAsInput();
    }
    
    public function checkString(): bool {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before performing string check.");
        }
        return $this->validateAsString();
    }

    public function count(): int {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before counting matches.");
        }
        $matches = $this->getAllMatches();
        return $matches ? count($matches) : 0;
    }
    
    public function toRegex(): string {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before converting to regex.");
        }
        return $this->pattern->getPattern();
    }
    
    public function replace(callable $replaceFunction): string {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before running replace.");
        }
        $matches = $this->getAllMatches();
        
        if ($matches) {
            $matches = $this->removeDuplicates($matches);
            $replaced = $this->str;
            foreach ($matches as $match) {
                $replaced = str_replace($match, $replaceFunction($match), $replaced);
            }
            return $replaced;
        }
    }
    
    public function search(string|callable $keywordOrPattern): mixed {

        if (is_callable($keywordOrPattern)) {
            $matches = $this->searchBySubpattern($keywordOrPattern);
        } 

        if (is_string($keywordOrPattern)) {
            $matches = $this->searchByKeyword($keywordOrPattern);
        }

        if ($matches) {
            $matches = array_map('trim', $matches);
            return $this->returnArrayOrCollection($matches);
        }

        return null;
    }
    
    public function searchReverse(string|callable $keywordOrPattern): mixed {

        if (is_callable($keywordOrPattern)) {
            $matches = $this->exceptBySubpattern($keywordOrPattern);
        } 

        if (is_string($keywordOrPattern)) {
            $matches = $this->exceptByKeyword($keywordOrPattern);
        }

        if ($matches) {
            $filteredMatches = array_filter(array_map('trim', $matches), function($match) {
                return !empty($match);
            });
            $filteredMatches = array_values($filteredMatches);
            return $this->returnArrayOrCollection($filteredMatches);
        }

        return null;
    }

    public function swap(string|callable $stringOrCallback): mixed {
        // Check if the pattern is set
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before setting options.");
        }
        // Check if groups are enabled
        if (!$this->getReturnGroups()) {
            throw new \LogicException("Swap must be used with groups (group or namedGroup methods).");
        }

        $results = $this->get();
        if (is_callable($stringOrCallback)) {
            $matches = $this->swapByCallback($stringOrCallback, $results);
        }
        if (is_string($stringOrCallback)) {
            $matches = $this->swapByString($stringOrCallback, $results);
        }

        return $this->returnArrayOrCollection($matches);
    }

    // Actions END


    // In cases when pattern doesn't allow setting the options (like BuilderPattern)
    public function setOptions(array|callable $config): self {
        // Check if the pattern is set
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before setting options.");
        }

        // Handle options array scenario
        if (is_array($config) && !empty($config)) {
            $this->processConfigArray($config);
        }
        // Handle callback scenario
        else if (is_callable($config)) {
            $this->processCallback($config);
        } 

        return $this;
    }
    
    /**
     * Registers a single pattern in the Builder.
     *
     * @param PatternContract $pattern The pattern to register.
     * @return self The Builder instance, for method chaining.
     */
    public function registerPattern(PatternContract $pattern): self {
        $this->patterns[] = $pattern;
        return $this;
    }
    
    /**
     * Registers multiple patterns in the Builder.
     *
     * @param array $patterns An array of patterns to register.
     * @return self The Builder instance, for method chaining.
     */
    public function registerPatterns(array $patterns): self {
        foreach ($patterns as $pattern) {
            if (is_subclass_of($pattern, PatternContract::class)) {
                $this->registerPattern(new $pattern());
            } else {
                throw new \InvalidArgumentException("Class " . get_class($pattern) . " must implement PatternContract");
            }
        }
        return $this;
    }
    
    /**
     * Retrieves the list of registered patterns.
     *
     * @return array An array of registered patterns.
     */
    public function getPatterns(): array {
        return $this->patterns;
    }

    // Expression flag methods:

    public function asCaseInsensitive(): self {
        $this->pattern->addExpressionFlag("i");
        return $this;
    }

    public function asMultiline(): self {
        $this->pattern->addExpressionFlag("m");
        return $this;
    }

    public function asSingleline(): self {
        $this->pattern->addExpressionFlag("s");
        return $this;
    }

    public function asUnicode(): self {
        $this->pattern->addExpressionFlag("u");
        return $this;
    }

    public function asSticky(): self {
        $this->pattern->addExpressionFlag("y");
        return $this;
    }

    public function setReturnGroups(bool $enable): self {
        $this->returnGroups = $enable;
        return $this;
    }

    public function getReturnGroups(): bool {
        return $this->returnGroups;
    }

    public function setNamedGroups(bool $enable): self {
        $this->namedGroups = $enable;
        return $this;
    }

    public function getNamedGroups(): bool {
        return $this->namedGroups;
    }

    /**
     * Dynamically handles calls to pattern methods.
     *
     * This method is invoked when a method is called on the Builder that matches the name of a registered pattern.
     *
     * @param string $name The name of the method being called.
     * @param array $args The arguments passed to the method.
     * @return self The Builder instance, for method chaining.
     * @throws \LogicException If no patterns are registered or the pattern name is not found.
     */
    public function __call(
        $name,
        $args
    ): self {
        if (empty($this->patterns)) {
            throw new \LogicException("No patterns are registered in Builder.");
        }

        foreach ($this->patterns as $pattern) {
            if ($pattern::$name == $name) {
                // Set needed pattern
                $this->pattern = new $pattern();

                $options = $pattern::execute(...$args);
                $this->processConfigArray($options);

                return $this;
            }
        }

        throw new \LogicException("Pattern with name $name not found.");
    }

}

<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\OptionsManager;
use Maestroerror\EloquentRegex\OptionsBuilder;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\BuilderPatternMethods;
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

    use BuilderPatternMethods;

    protected string $str;
    protected PatternContract $pattern;
    protected OptionsManager $manager;

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

    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
        // @todo add patterns via config
    }

    protected function processConfigArray(array $options) {
        // Build options in options manager
        $this->manager->buildOptions($options);
        // Set used options to pattern
        $this->pattern->setOptions($this->manager->getUsedOptions());
    }

    protected function processCallback(callable $callback) {
        $optionsBuilder = new OptionsBuilder();
        $optionsBuilder = $callback($optionsBuilder);
        $this->processConfigArray($optionsBuilder->getOptions());
    }

    protected function validateAsInput(): bool {
        if (!$this->pattern->validateInput($this->str)) {
            return false;
        }
        return true;
    }

    protected function validateAsString(): bool {
        if (!$this->pattern->validateMatches($this->str)) {
            return false;
        }
        return true;
    }

    protected function getAllMatches(): ?array {
        return $this->pattern->getMatches($this->str);
    }

    /**
     * Checks if a pattern is set.
     *
     * @return bool Returns true if a pattern is set, false otherwise.
     */
    protected function patternIsSet(): bool {
        return isset($this->pattern) && !empty($this->pattern);
    }
    
    /* Public methods (API) */

    public function setString(string $str): void {
        $this->str = $str;
    }

    public function get(): ?array {
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before getting matches.");
        }
        return $this->getAllMatches();
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

    // In cases when pattern doesn't allow setting the options (like BuilderPattern)
    public function setOptions(array|callable $config): void {
        // Check if the pattern is set
        if (!$this->patternIsSet()) {
            throw new \LogicException("Pattern must be set before setting options.");
        }

        // Handle options array scenario
        if (is_array($config)) {
            $this->processConfigArray($config);
        }
        // Handle callback scenario
        else if (is_callable($config)) {
            $this->processCallback($config);
        } 
    }
    
    public function registerPattern(PatternContract $pattern): self {
        $this->patterns[] = $pattern;
        return $this;
    }
    
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
    
    public function getPatterns(): array {
        return $this->patterns;
    }

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

<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\CharacterClassesTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\SpecificCharsTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\AnchorsTrait;
use Maestroerror\EloquentRegex\Builder;


class BuilderPattern extends BasePattern {

    // BuilderPattern doesn't need the "execute" method (src\Traits\Pattern.php)
    use CharacterClassesTrait, SpecificCharsTrait, AnchorsTrait;

    /**
     * @var array Array of options for the pattern.
     */
    protected array $options = [];

    /**
     * @var string String to hold the constructed regex pattern.
     */
    protected string $pattern = "";

    /**
     * @var bool Flag to indicate if the next quantifier should be lazy (non-greedy).
     */
    protected bool $lazy = false;

    /**
     * @var BuilderContract Reference to the main Builder object.
     */
    protected BuilderContract $builder; 

    /**
     * Constructor for BuilderPattern.
     *
     * @param BuilderContract $builder An instance of BuilderContract.
     */
    public function __construct(BuilderContract $builder = new Builder()) {
        $this->builder = $builder;
    }

    // Builder class implementation methods START

    public function end(array|callable $config = []): BuilderContract {
        return $this->builder->setOptions($config); // Return the Builder object
    }

    public function get(): ?array {
        return $this->builder->get();
    }
    
    public function check(): bool {
        return $this->builder->check();
    }
    
    public function checkString(): bool {
        return $this->builder->checkString();
    }
    
    public function count(): int {
        return $this->builder->count();
    }
    
    public function toRegex(): string {
        return $this->builder->toRegex();
    }

    // Builder class implementation methods END

    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}$/" . $this->expressionFlags;
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}/" . $this->expressionFlags;
    }

    /**
     * Applies a quantifier to a given regex pattern.
     *
     * @param string $pattern The regex pattern to which the quantifier will be applied.
     * @param string|null $quantifier The quantifier to apply. Can be 'zeroOrMore', 'oneOrMore', or 'optional'.
     * @return string The modified pattern with the quantifier applied.
     */
    private function applyQuantifier(string $pattern, string|null $quantifier): string {
        switch ($quantifier) {
            case 'zeroOrMore' || '0>' || '0+':
                $p = $pattern . '*';
                return $this->lazy ? $this->addLazy($p) : $p;
            case 'oneOrMore' || '1>' || '1+':
                $p = $pattern . '+';
                return $this->lazy ? $this->addLazy($p) : $p;
                case 'optional' || '?' || '|':
                return $pattern . '?';
            default:
                return $pattern;
        }
    }

    /**
     * Generates a regex quantifier string based on length parameters.
     *
     * @param int|null $length Exact length for the quantifier.
     * @param int $minLength Minimum length for the quantifier.
     * @param int $maxLength Maximum length for the quantifier.
     * @return string The generated regex quantifier string.
     */
    private function getLengthOption(int|null $length = null, int $minLength = 0, int $maxLength = 0): string {
        if (is_int($length) && $length >= 0) {
            $qntf = "{" . $length . "}";
            return $this->lazy ? $this->addLazy($qntf) : $qntf;
        }
    
        if ($minLength > 0 && $maxLength > 0) {
            $qntf = "{" . $minLength . "," . $maxLength . "}";
            return $this->lazy ? $this->addLazy($qntf) : $qntf;
        } else if ($minLength > 0) {
            $qntf = "{" . $minLength . ",}";
            return $this->lazy ? $this->addLazy($qntf) : $qntf;
        } else if ($maxLength > 0) {
            $qntf = "{0," . $maxLength . "}";
            return $this->lazy ? $this->addLazy($qntf) : $qntf;
        }
    
        $qntf = "+";  // Default case, one or more times
        return $this->lazy ? $this->addLazy($qntf) : $qntf;
    }

    /**
     * Adds a lazy (non-greedy) modifier to a quantifier
     * and sets $lazy to false for ensuring single use
     *
     * @param string $quantifier The quantifier to which the lazy modifier will be added.
     * @return string The quantifier with the lazy modifier applied.
     */
    private function addLazy($quantifier): string {
        $this->lazy = false;
        return $quantifier . "?";
    }

    /**
     * Creates a lazy (non-greedy) quantifier for the next method call.
     *
     * @return self
     */
    public function lazy(): self {
        $this->lazy = true;
        return $this;
    }

    /**
     * Adds a new set of characters.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @return self
     */
    public function charSet(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '[' . $subPattern->getPattern() . ']';
        return $this;
    }

    /**
     * Adds a new set of denied characters.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @return self
     */
    public function negativeCharSet(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '[^' . $subPattern->getPattern() . ']';
        return $this;
    }

    /**
     * Adds a new grouped subpattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @return self
     */
    public function group(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '(' . $subPattern->getPattern() . ')';
        return $this;
    }

    /**
     * Adds a new non-capturing grouped subpattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @return self
     */
    public function nonCapturingGroup(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '(?:' . $subPattern->getPattern() . ')';
        return $this;
    }
    
    /**
     * Adds an alternation pattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the alternation.
     * @return self
     */
    public function orPattern(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '|' . $builder->getPattern();
        return $this;
    }

    /**
     * Adds a positive lookahead assertion.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the assertion.
     * @return self
     */
    public function lookAhead(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?=' . $builder->getPattern() . ')';
        return $this;
    }

    /**
     * Adds a positive lookbehind assertion.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the assertion.
     * @return self
     */
    public function lookBehind(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?<=' . $builder->getPattern() . ')';
        return $this;
    }

    /**
     * Adds a negative lookahead assertion.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the assertion.
     * @return self
     */
    public function negativeLookAhead(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?!' . $builder->pattern . ')';
        return $this;
    }

    /**
     * Adds a negative lookbehind assertion.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the assertion.
     * @return self
     */
    public function negativeLookBehind(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?<!' . $builder->pattern . ')';
        return $this;
    }

    /**
     * Adds a raw regex string to the pattern.
     *
     * @param string $regex The raw regex string to add.
     * @return self
     */
    public function addRawRegex(string $regex): self {
        $this->pattern .= $regex;
        return $this;
    }

    /**
     * Wraps a given regex string in a non-capturing group and adds it to the pattern.
     *
     * @param string $regex The regex string to wrap and add.
     * @return self
     */
    public function addRawNonCapturingGroup(string $regex): self {
        $this->pattern .= '(?:' . $regex . ')';
        return $this;
    }
}
<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\CharacterClassesTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\SpecificCharsTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\AnchorsTrait;
use Maestroerror\EloquentRegex\Builder;


class BuilderPattern extends BasePattern {

    // BuilderPattern doesn't need the execute method (src\Traits\Pattern.php)
    use CharacterClassesTrait, SpecificCharsTrait, AnchorsTrait;

    protected array $options = [];
    protected string $pattern = "";
    protected bool $lazy = false;
    protected BuilderContract $builder; // Reference to the main Builder object

    public function __construct(BuilderContract $builder = new Builder()) {
        $this->builder = $builder;
    }

    public function end(): BuilderContract {
        return $this->builder; // Return the Builder object
    }

    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}$/" . $this->expressionFlags;
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}/" . $this->expressionFlags;
    }

    private function applyQuantifier($pattern, $quantifier) {
        switch ($quantifier) {
            case 'zeroOrMore' || '0>' || '0+':
                return $pattern . '*';
            case 'oneOrMore' || '1>' || '1+':
                return $pattern . '+';
            case 'optional' || '?' || '|':
                return $pattern . '?';
            default:
                return $pattern;
        }
    }

    private function getLengthOption($length = null, $minLength = 0, $maxLength = 0): string {
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

    private function addLazy($quantifier): string {
        $this->lazy = false;
        return $quantifier . "?";
    }

    // The next quantifier-based method will considered as lazy (adds "?")
    public function lazy(): self {
        $this->lazy = true;
        return $this;
    }

    public function set(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '[' . $subPattern->getPattern() . ']';
        return $this;
    }

    public function negativeSet(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '[^' . $subPattern->getPattern() . ']';
        return $this;
    }

    public function group(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '(' . $subPattern->getPattern() . ')';
        return $this;
    }

    public function nonCapturingGroup(callable $callback): self {
        $subPattern = new self();
        $callback($subPattern);
        $this->pattern .= '(?:' . $subPattern->getPattern() . ')';
        return $this;
    }
    
    public function orPattern(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '|' . $builder->getPattern();
        return $this;
    }

    public function lookAhead(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?=' . $builder->getPattern() . ')';
        return $this;
    }

    public function lookBehind(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?<=' . $builder->getPattern() . ')';
        return $this;
    }

    public function negativeLookAhead(callable $callback): self {
        $builder = new self();
        $callback($builder);
        $this->pattern .= '(?!' . $builder->pattern . ')';
        return $this;
    }

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
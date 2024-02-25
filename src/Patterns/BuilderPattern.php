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
            return "{" . $length . "}";
        }
    
        if ($minLength > 0 && $maxLength > 0) {
            return "{" . $minLength . "," . $maxLength . "}";
        } else if ($minLength > 0) {
            return "{" . $minLength . ",}";
        } else if ($maxLength > 0) {
            return "{0," . $maxLength . "}";
        }
    
        return "+";  // Default case, one or more times
    }

    public function group(callable $callback): self {
        $subPattern = new self($this->builder);
        $callback($subPattern);
        $this->pattern .= '(' . $subPattern->getPattern() . ')';
        return $this;
    }

    public function nonCapturingGroup(callable $callback): self {
        $subPattern = new self($this->builder);
        $callback($subPattern);
        $this->pattern .= '(?:' . $subPattern->getPattern() . ')';
        return $this;
    }
    
}
<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Contracts\BuilderContract;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\CharacterClassesTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\SpecificCharsTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\AnchorsTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\GroupsTrait;
use Maestroerror\EloquentRegex\Builder;


class BuilderPattern extends BasePattern {

    // BuilderPattern doesn't need the "execute" method (src\Traits\Pattern.php)
    use CharacterClassesTrait, SpecificCharsTrait, AnchorsTrait, GroupsTrait;

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
    private function applyQuantifier(string $pattern, string|null $q): string {

        if (!$q) {
            return $pattern;
        }
        
        if ($q == 'zeroOrMore' || $q == '0>' || $q == '0+' || $q == '*') {
            $p = "(?:" . $pattern . ')*';
            return $this->lazy ? $this->addLazy($p) : $p;
        } elseif ($q == 'oneOrMore' || $q == '1>' || $q == '1+' || $q == '+') {
            $p = "(?:" . $pattern . ')+';
            return $this->lazy ? $this->addLazy($p) : $p;
        } elseif ($q == 'optional' || $q == '?' || $q == '|') {
            $p = "(?:" . $pattern . ')?';
            return $this->lazy ? $this->addLazy($p) : $p;
        }

        if (is_int($q)) {
            $p = "(?:" . $pattern . "){".$q."}";
            return $this->lazy ? $this->addLazy($p) : $p;
        } elseif (preg_match("/^\d{1,10}$/", $q)) {
            $p = "(?:" . $pattern . '){'.$q.'}';
            return $this->lazy ? $this->addLazy($p) : $p;
        } elseif (preg_match("/^\d{1,10},\d{1,10}$/", $q)) {
            $range = explode(",", $q);
            $f = $range[0];
            $s = $range[1];
            $p = "(?:" . $pattern . ")" . "{" . $f . "," . $s ."}";
            return $this->lazy ? $this->addLazy($p) : $p;
        }

        return $pattern;
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
        if (is_int($length) && $length > 0) {
            $qntf = "{" . $length . "}";
            return $this->lazy ? $this->addLazy($qntf) : $qntf;
        } elseif ($length === 0) {
            return "";
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

    
}
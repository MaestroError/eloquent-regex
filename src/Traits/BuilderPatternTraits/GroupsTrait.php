<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait GroupsTrait {

    /**
     * Adds a new set of characters.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @param ?string $q a Quantifier
     * @return self
     */
    public function charSet(callable $callback, ?string $q = null): self {
        $subPattern = new self();
        $callback($subPattern);
        $p = '[' . $subPattern->getPattern() . ']';
        $this->pattern .= $q ? $this->applyQuantifier($p, $q) : $p;
        return $this;
    }

    /**
     * Adds a new set of denied characters.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @param ?string $q a Quantifier
     * @return self
     */
    public function negativeCharSet(callable $callback, ?string $q = null): self {
        $subPattern = new self();
        $subPattern->inCharSet();
        $callback($subPattern);
        $p = '[^' . $subPattern->getPattern() . ']';
        $this->pattern .= $q ? $this->applyQuantifier($p, $q) : $p;
        return $this;
    }

    /**
     * Adds a new grouped subpattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @param ?string $q a Quantifier
     * @return self
     */
    public function group(callable $callback, ?string $q = null): self {
        $this->builder->setReturnGroups(true);
        $subPattern = new self();
        $callback($subPattern);
        $p = $subPattern->getPattern();
        $this->pattern .= $q ? $this->applyQuantifier($p, $q) : '(' . $p . ')';
        return $this;
    }

    /**
     * Adds a new non-capturing grouped subpattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the subpattern.
     * @param ?string $q a Quantifier
     * @return self
     */
    public function nonCapturingGroup(callable $callback, ?string $q = null): self {
        $subPattern = new self();
        $callback($subPattern);
        $p = '(?:' . $subPattern->getPattern() . ')';
        $this->pattern .= $q ? $this->applyQuantifier($p, $q) : $p;
        return $this;
    }
    
    /**
     * Adds an alternation pattern.
     *
     * @param callable $callback A callback that receives a BuilderPattern instance to define the alternation.
     * @param ?string $q a Quantifier
     * @return self
     */
    public function orPattern(callable $callback, ?string $q = null): self {
        $builder = new self();
        $callback($builder);
        $p = $builder->getPattern();
        $this->pattern .= $q ? '|' . $this->applyQuantifier($p, $q) : '|' . $p;
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

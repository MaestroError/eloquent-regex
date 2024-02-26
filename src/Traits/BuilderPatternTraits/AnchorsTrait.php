<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait AnchorsTrait {

    // Anchors START

    /**
     * Adds a word boundary marker at the current position in the pattern.
     *
     * @return self The current instance of the BuilderPattern for method chaining.
     */
    public function wordBoundary(): self {
        $this->pattern .= '\\b';
        return $this;
    }

    /**
     * Adds a word border marker at the current position in the pattern.
     * Note: This method is similar to 'wordBoundary' and can be used interchangeably.
     *
     * @return self The current instance of the BuilderPattern for method chaining.
     */
    public function wordBorder(): self {
        $this->pattern .= '\\b';
        return $this;
    }

    /**
     * Encloses the current pattern within word boundaries.
     * This ensures that the pattern matches only a complete word.
     *
     * @return self The current instance of the BuilderPattern for method chaining.
     */
    public function asWord(): self {
        $this->pattern = '\\b' . $this->pattern . '\\b';
        return $this;
    }

    // Anchors END
}

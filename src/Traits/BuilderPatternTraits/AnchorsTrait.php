<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait AnchorsTrait {

    // Anchors START

    public function wordBoundary(): self {
        $this->pattern .= '\\b';
        return $this;
    }

    public function wordBorder(): self {
        $this->pattern .= '\\b';
        return $this;
    }

    public function asWord(): self {
        $this->pattern = '\\b' . $this->pattern . '\\b';
        return $this;
    }

    // Anchors END
}

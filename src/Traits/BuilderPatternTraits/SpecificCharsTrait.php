<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait SpecificCharsTrait {

    // Specific Characters START

    public function exact($string, $caseSensitive = true, $quantifier = null): self {
        $escapedString = preg_quote($string, '/');
        $pattern = $caseSensitive ? $escapedString : "(?i)" . $escapedString;
        $this->pattern .= $this->applyQuantifier($pattern, $quantifier);
        return $this;
    }
    
    public function character($char, $caseSensitive = true, $quantifier = null): self {
        $escapedChar = preg_quote($char, '/');
        $pattern = $caseSensitive ? $escapedChar : "(?i)" . $escapedChar;
        $this->pattern .= $this->applyQuantifier($pattern, $quantifier);
        return $this;
    }

    public function dot($quantifier = null): self {
        $this->pattern .= '.'; // Matches any character except newline
        $this->pattern = $this->applyQuantifier($this->pattern, $quantifier);
        return $this;
    }    
    
    // Specific Characters END
}

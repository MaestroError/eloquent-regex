<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;

class BuilderPattern extends BasePattern {
    protected array $options = [];
    protected string $pattern = "";

    public function lowercaseText($length = null) {
        $this->pattern .= "[a-z]";
        $this->pattern .= "{$length}";
    }

    public function textLowercase($length = null) {
        $this->pattern .= "[a-z]";
        $this->pattern .= $this->getLengthOption($length);
    }

    public function getLengthOption($length = null, $minLength = 0, $maxLength = 0) {
        if (is_int($length) && $length >= 0) {
            return "{$length}";
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
    
}
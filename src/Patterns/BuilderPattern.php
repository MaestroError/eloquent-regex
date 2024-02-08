<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\CharacterClassesTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\SpecificCharsTrait;
use Maestroerror\EloquentRegex\Traits\BuilderPatternTraits\AnchorsTrait;


class BuilderPattern extends BasePattern {

    use CharacterClassesTrait, SpecificCharsTrait, AnchorsTrait;

    protected array $options = [];
    protected string $pattern = "";

    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}$/";
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}/";
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
    
}
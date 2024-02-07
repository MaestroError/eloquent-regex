<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\AddToPatternTrait;
use Maestroerror\EloquentRegex\Traits\IsOptionalTrait;

class CharacterOption implements OptionContract {

    use AddToPatternTrait, IsOptionalTrait;

    private $allowedCharacters = [];
    private $excludedCharacters = [];
    private $minUppercase = 0;
    private $minLowercase = 0;

    public function validate(string $input): bool {
        $uppercaseCount = preg_match_all('/[A-Z]/', $input);
        $lowercaseCount = preg_match_all('/[a-z]/', $input);

        if ($this->minUppercase > 0 && $uppercaseCount < $this->minUppercase) {
            return false;
        }

        if ($this->minLowercase > 0 && $lowercaseCount < $this->minLowercase) {
            return false;
        }

        foreach (str_split($input) as $char) {
            if (!empty($this->allowedCharacters) && !in_array($char, $this->allowedCharacters, true)) {
                return false; // Character not in the allowed list
            }

            if (in_array($char, $this->excludedCharacters, true)) {
                return false; // Character is in the excluded list
            }
        }

        return true;
    }

    public function build(): string {
        // Building the pattern based on allowed and excluded characters
        $pattern = '';
        if (!empty($this->allowedCharacters)) {
            $pattern .= '[' . implode('', array_map('preg_quote', $this->allowedCharacters)) . ']';
        } else {
            $pattern .= '.';
        }

        if (!empty($this->excludedCharacters)) {
            $pattern .= '[^' . implode('', array_map('preg_quote', $this->excludedCharacters)) . ']';
        }

        if ($this->isOptional) {
            $pattern .= '?';
        }

        return $pattern;
    }

    public function reset() {
        $this->allowedCharacters = [];
        $this->excludedCharacters = [];
        $this->minUppercase = 0;
        $this->minLowercase = 0;
        $this->isOptional = false;
        return $this;
    }

    public function description(): string {
        return "Character option allowing: " . implode(', ', $this->allowedCharacters) .
               "; excluding: " . implode(', ', $this->excludedCharacters) .
               "; min uppercase: $this->minUppercase; min lowercase: $this->minLowercase";
    }

    // Additional methods
    public function allow(array $characters) {
        $this->allowedCharacters = $characters;
        return $this;
    }

    public function exclude(array $characters) {
        $this->excludedCharacters = $characters;
        return $this;
    }

    public function minUppercase(int $count) {
        $this->minUppercase = $count;
        return $this;
    }

    public function minLowercase(int $count) {
        $this->minLowercase = $count;
        return $this;
    }
}

<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\IsOptionalTrait;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class CharacterOption implements OptionContract {

    use IsOptionalTrait, ValidateUsingRegexTrait;

    private $allowedCharacters = [];
    private $excludedCharacters = [];
    private $minUppercase = 0;
    private $minLowercase = 0;
    private $validateUsingRegex = true;

    public function validate(string $input): bool {
        if ($this->validateUsingRegex) {
            return $this->validateUsingRegex($input);
        }

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

        // Lookahead for minimum uppercase
        if ($this->minUppercase > 0) {
            $pattern .= '(?=(?:.*[A-Z]){' . $this->minUppercase . ',})';
        }

        // Lookahead for minimum lowercase
        if ($this->minLowercase > 0) {
            $pattern .= '(?=(?:.*[a-z]){' . $this->minLowercase . ',})';
        }

        // Handle allowed characters
        if (!empty($this->allowedCharacters)) {
            $allowedPattern = '[' . implode('', array_map('preg_quote', $this->allowedCharacters)) . ']+';
        } else {
            // $allowedPattern = '.*'; // If no allowed characters are specified, allow anything.
            $allowedPattern = '.*'; // If no allowed characters are specified, allow anything.
        }

        // Handle excluded characters
        if (!empty($this->excludedCharacters)) {
            $excludedPattern = '(?!.*[' . implode('', array_map('preg_quote', $this->excludedCharacters)) . '])';
        } else {
            $excludedPattern = ''; // If no excluded characters, no restriction.
        }

        $pattern .= $excludedPattern . $allowedPattern;

        if ($this->isOptional) {
            $pattern = "(?:$pattern)?";
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

    // Option methods
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

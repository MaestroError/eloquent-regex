<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class CharOption implements OptionContract {

    private $minSpecialCharacters = 0;
    private $maxSpecialCharacters = null;
    private $onlyLowercase = false;
    private $onlyUppercase = false;

    public function validate(string $input): bool {
        $uppercaseCount = preg_match_all('/[A-Z]/', $input);
        $lowercaseCount = preg_match_all('/[a-z]/', $input);
        $inputCount = strlen($input);

        if ($this->onlyLowercase) {
            if ($lowercaseCount != $inputCount) {
                return false;
            }
        }

        if ($this->onlyUppercase) {
            if ($uppercaseCount != $inputCount) {
                return false;
            }
        }

        if (!$this->checkSpecialChars($input)) {
            return false;
        }
        return true;
    }

    public function build(): string {
        return "";
    }
    
    private function checkSpecialChars(string $input) {
        // Special character count validation
        if ($this->minSpecialCharacters > 0 || $this->maxSpecialCharacters !== null) {
            $specialCharsCount = preg_match_all('/[^\w\s]/', $input);
            if ($this->minSpecialCharacters > 0 && $specialCharsCount < $this->minSpecialCharacters) {
                return false; // Not enough special characters
            }
            if ($this->maxSpecialCharacters !== null && $specialCharsCount > $this->maxSpecialCharacters) {
                return false; // Too many special characters
            }
        }
        return true;
    }

    // Option methods:
    public function minSpecialCharacters(int $count) {
        $this->minSpecialCharacters = $count;
        return $this;
    }

    public function maxSpecialCharacters(int $count) {
        $this->maxSpecialCharacters = $count;
        return $this;
    }

    public function noSpecialCharacters(bool $disable = true) {
        if ($disable) {
            $this->maxSpecialCharacters = 0;
        }
        return $this;
    }

    public function onlyLowercase(bool $only = true) {
        $this->onlyLowercase = $only;
        return $this;
    }

    public function onlyUppercase(bool $only = true) {
        $this->onlyUppercase = $only;
        return $this;
    }
}

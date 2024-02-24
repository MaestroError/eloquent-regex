<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class OnlyAlphanumericOption implements OptionContract {

    use ValidateUsingRegexTrait;

    private bool $allowOnlyAlphanumeric = true;
    private $validateUsingRegex = true;

    public function validate(string $input): bool {
        if ($this->validateUsingRegex) {
            return $this->validateUsingRegex($input);
        }

        if ($this->allowOnlyAlphanumeric) {
            // Check if input contains only alphanumeric characters
            return ctype_alnum($input);
        }
        return true;
    }

    public function build(): string {
        // Returns a regex pattern that matches alphanumeric characters if the option is enabled
        return $this->allowOnlyAlphanumeric ? '[a-zA-Z0-9]+' : '.+';
    }

    public function onlyAlphanumeric(bool $only = true): self {
        $this->allowOnlyAlphanumeric = $only;
        return $this;
    }
}

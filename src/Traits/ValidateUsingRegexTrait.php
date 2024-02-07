<?php

namespace Maestroerror\EloquentRegex\Traits;

trait ValidateUsingRegexTrait {
    public function validateUsingRegex(string $input): bool {
        $regex = "/^" . $this->build() . "$/";
        return preg_match($regex, $input) === 1;
    }
}



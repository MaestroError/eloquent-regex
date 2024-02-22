<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class CountryCodeOption implements OptionContract {

    private string $countryCode = '';

    public function validate(string $input): bool {
        if ($this->countryCode === '') {
            return true; // If no country code is set, pass validation by default
        }

        return strpos($input, '+' . $this->countryCode) === 0 || 
               strpos($input, $this->countryCode) === 0;
    }

    public function build(): string {
        // This method is not used as the validation is done in PHP and not with regex.
        return "";
    }

    public function setCountryCode(string $code): self {
        $this->countryCode = $code;
        return $this;
    }
}

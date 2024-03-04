<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class SpecificCurrenciesOption implements OptionContract {

    use ValidateUsingRegexTrait;

    private $specificCurrencies = [];

    public function validate(string $input): bool {
        if (empty($this->specificCurrencies)) {
            return true; // If no specific currencies are set, pass validation by default
        }

        // Build regex pattern for specific currencies
        $pattern = $this->build();
        return preg_match("/$pattern/", $input) > 0;
    }

    public function build(): string {
        if (empty($this->specificCurrencies)) {
            return ''; // If no specific currencies are set, no pattern needed
        }

        $escapedCurrencies = array_map('preg_quote', $this->specificCurrencies);
        $pattern = implode('|', $escapedCurrencies);

        return $pattern; // Returns a regex pattern to match any of the specific currencies
    }

    public function setSpecificCurrencies(array|string $currencies): self {
        if (is_string($currencies)) {
            $currencies = explode(",", $currencies);
        }
        $this->specificCurrencies = $currencies;
        return $this;
    }

    public function onlyUSD($only = true) {
        if ($only) {
            $this->specificCurrencies = ["$"];
        }
        return $this;
    }

    public function onlyEUR($only = true) {
        if ($only) {
            $this->specificCurrencies = ["€"];
        }
        return $this;
    }

    public function onlyGBP($only = true) {
        if ($only) {
            $this->specificCurrencies = ["£"];
        }
        return $this;
    }

    public function onlyGEL($only = true) {
        if ($only) {
            $this->specificCurrencies = ["₾"];
        }
        return $this;
    }
}

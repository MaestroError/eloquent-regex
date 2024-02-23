<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class CardTypeOption implements OptionContract {

    use ValidateUsingRegexTrait;

    private $onlyVisa = false;
    private $onlyMasterCard = false;
    private $onlyAmex = false;

    public function validate(string $input): bool {
        $input = str_replace(" ", "", $input);
        $input = str_replace("-", "", $input);
        return $this->validateUsingRegex($input);
    }

    public function build(): string {
        $patterns = [];

        if ($this->onlyVisa) {
            $patterns[] = '4[0-9]{12}(?:[0-9]{3})?'; // Visa card numbers
        }

        if ($this->onlyMasterCard) {
            $patterns[] = '5[1-5][0-9]{14}'; // MasterCard numbers
        }

        if ($this->onlyAmex) {
            $patterns[] = '^3[47][0-9]{13}$'; // American Express card numbers
        }

        return implode('|', $patterns); // Combine the patterns with OR
    }

    public function onlyVisa(bool $only = true): self {
        $this->onlyVisa = $only;
        return $this;
    }

    public function onlyMasterCard(bool $only = true): self {
        $this->onlyMasterCard = $only;
        return $this;
    }

    public function onlyAmex(bool $only = true): self {
        $this->onlyAmex = $only;
        return $this;
    }

    public function allowCardTypes(string $cardTypes): self {
        $types = explode(',', $cardTypes);
        
        foreach ($types as $type) {
            switch (trim(strtolower($type))) {
                case 'visa':
                    $this->onlyVisa = true;
                    break;
                case 'mastercard':
                    $this->onlyMasterCard = true;
                    break;
                case 'amex':
                    $this->onlyAmex = true;
                    break;
                // Add cases for additional card types if necessary
            }
        }
    
        return $this;
    }
}

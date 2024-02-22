<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class CurrencyPattern extends BasePattern {

    use Pattern;

    protected string $pattern = "(?:\p{Sc}){1}\s?[\d,]+(?:\.\d{1,3})?\b";

    public static string $name = "currency";

    public static array $args = [
        "minDigits",
        "maxDigits",
        "specificCurrencies",
    ];

    public function getInputValidationPattern(): string {
        return "/^{$this->pattern}$/u";
    }

    public function getMatchesValidationPattern(): string {
        return "/{$this->pattern}/u";
    }
}

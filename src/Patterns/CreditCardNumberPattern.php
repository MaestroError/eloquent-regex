<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class CreditCardNumberPattern extends BasePattern {

    use Pattern;

    // Updated regex pattern for credit card numbers
    protected string $pattern = "(?:(?:(?:4\\d{3})|(?:5[1-5]\\d{2})|(?:6(?:011|5\\d{2})))[- ]?\\d{4}[- ]?\\d{4}[- ]?\\d{4})|(?:(?:3[47]\\d{2})[- ]?\\d{6}[- ]?\\d{5})";

    public static string $name = "creditCardNumber";

    public static array $args = ["cardTypes"];
}

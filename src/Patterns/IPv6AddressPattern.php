<?php
namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class IPv6AddressPattern extends BasePattern {

    use Pattern;

    // Regex pattern for IPv6
    protected string $pattern = '\b([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}\b';

    public static string $name = "ipv6Address";

    public static array $args = [];

    public static array $defaultOptions = [
        "validIPv6" => true
    ];
}

<?php

// require_once __DIR__ . '/vendor/autoload.php';
class SimplifiedRegex {
    protected $pattern = '';
    protected $config;

    public function __construct() {
        $this->config = new class {
            public $minLength = 0;
            public $minUppercase = 0;
            public $minNumber = 0;
        };
    }

    public function textAndNumbers(callable $configurator) {
        $this->pattern = '(?=.*[a-zA-Z0-9])';
        $configurator($this->config);
        return $this;
    }

    public function get() {
        $length = $this->config->minLength;
        $uppercase = $this->config->minUppercase > 0 ? "(?=(?:.*[A-Z]).{{$this->config->minUppercase},})" : '';
        $number = $this->config->minNumber > 0 ? "(?=(?:.*\d).{{$this->config->minNumber},})" : '';
        return "/^{$this->pattern}{$uppercase}{$number}.{{$length},}$/";
    }
}

// Example usage
$simpleRegex = new SimplifiedRegex();
$pattern = $simpleRegex->textAndNumbers(function($options) {
    $options->minLength = 8;
    $options->minUppercase = 1;
    $options->minNumber = 1;
})->get();
echo $pattern;
echo "\n";
echo preg_match($pattern, "Revaz1621");

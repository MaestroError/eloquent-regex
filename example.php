<?php

class SimplifiedRegex {
    protected $str;
    protected $pattern;

    public function __construct($str) {
        $this->str = $str;
        $this->pattern = '';
    }

    public function textUppercase($length) {
        $this->pattern .= "[A-Z]{{$length}}";
        return $this;
    }

    public function dash() {
        $this->pattern .= '-';
        return $this;
    }

    public function anyNumbers() {
        $this->pattern .= '\d+';
        return $this;
    }

    public function toRegex() {
        return "/{$this->pattern}/";
    }

    public function check() {
        return preg_match($this->toRegex(), $this->str);
    }

    public function get() {
        preg_match_all($this->toRegex(), $this->str, $matches);
        return $matches[0];
    }

    // Add more methods as needed
}

// Example usage
$SR = new SimplifiedRegex("RI-214");
$check = $SR->textUppercase(2)->dash()->anyNumbers()->check();
echo $check ? 'True' : 'False'; // True

// Getting multiple matches
$multiSR = new SimplifiedRegex("RI-214 sdjajkgjkdhfsgdkjfjkhagkjhs, RQ-466 sakfdsjg kl;sdfgf");
$matches = $multiSR->textUppercase(2)->dash()->anyNumbers()->get();
print_r($matches); // ["RI-214", "RQ-466"]

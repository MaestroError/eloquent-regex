<?php
class SimplifiedRegex {
    protected $str;
    protected $patterns = [];

    public function __construct($str) {
        $this->str = $str;
    }

    public function textUppercase($length) {
        $pattern = new TextUppercasePattern(); // Assuming TextUppercasePattern implements PatternContract
        $pattern->setOptions(new NumberOption(['min' => $length]));
        $this->patterns[] = $pattern;
        return $this;
    }

    public function dash() {
        $this->patterns[] = new DashPattern(); // Assuming DashPattern implements PatternContract
        return $this;
    }

    // ... other methods ...

    public function toRegex() {
        $regex = '';
        foreach ($this->patterns as $pattern) {
            $regex = $pattern->addToPattern($regex);
        }
        return "/$regex/";
    }

    // ... check and get methods ...
}

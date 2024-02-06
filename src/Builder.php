<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Patterns\TextAndNumbersPattern;
use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\OptionsManager;

class Builder {
    protected string $str;
    protected array $patterns = [];
    protected OptionsManager $manager;

    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
    }

    public function setTargetString(string $str) {
        $this->str = $str;
    }

    /* TextAndNumbersPattern implementation */
    public function textAndNumbers(int|array|callable $minLength) {
        $pattern = new TextAndNumbersPattern();
        $this->manager->buildOptions([
            "minLength" => $minLength
        ]);
        print_r($this->manager->returnUsedOptions());
        $pattern->setOptions($this->manager->returnUsedOptions());
        $this->patterns[] = $pattern;
        return $this;
    }

    protected function processArguments() {

    }

    protected function processConfigArray() {

    }

    protected function processCallback() {

    }

    public function get() {
        $regex = "";

        foreach ($this->patterns as $pattern) {
            $regex .= $pattern->build();
        }

        return "/" . $regex . "/";
    }

}

<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;
use Maestroerror\EloquentRegex\Contracts\PatternContract;
use Maestroerror\EloquentRegex\OptionsManager;

class Builder {
    protected string $str;
    protected array $patterns = [];
    protected OptionsManager $manager;
    protected string $regex = "";

    public function __construct(string $str = "") {
        $this->str = $str;
        $this->manager = new OptionsManager();
    }

    protected function processArguments() {

    }

    protected function processConfigArray() {

    }

    protected function processCallback() {

    }

    protected function build(): void {
        $this->regex = "";
        foreach ($this->patterns as $pattern) {
            $this->regex .= $pattern->build();
        }
        $this->regex = "/" . $this->regex . "/";
    }
    
    /* Public methods (API) */

    public function setTargetString(string $str): void {
        $this->str = $str;
    }

    public function get(): array {
        $this->build();
        $matches = [];
        preg_match_all($this->regex, $this->str, $matches);

        return $matches[0];
    }

    public function check(): string {
        $this->build();
        return preg_match($this->regex, $this->str) === 1;
    }

    public function toRegex(): string {
        $this->build();
        return $this->regex;
    }

    /* TextAndNumbersPattern implementation */
    public function textOrNumbers(int|array|callable $minLength): self {
        $pattern = new TextOrNumbersPattern();
        $this->manager->buildOptions([
            "minLength" => $minLength
        ]);
        $pattern->setOptions($this->manager->getUsedOptions());
        $this->patterns[] = $pattern;
        return $this;
    }

}

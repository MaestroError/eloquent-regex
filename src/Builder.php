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

    protected function validateAsInput(): bool {
        if (!empty($this->patterns)) {
            foreach ($this->patterns as $pattern) {
                if (!$pattern->validateInput($this->str)) {
                    return false;
                }
            }
            return true;
        }
    }

    protected function validateAsString(): bool {
        if (!empty($this->patterns)) {
            foreach ($this->patterns as $pattern) {
                if (!$pattern->validateMatches($this->str)) {
                    return false;
                }
            }
            return true;
        }
    }

    protected function countMatches(): int {
        $count = 0;
        if (!empty($this->patterns)) {
            foreach ($this->patterns as $pattern) {
                $count += $pattern->countMatches($this->str);
            }
        }
        return $count;
    }

    protected function getAllMatches(): array {
        $matches = [];
        if (!empty($this->patterns)) {
            foreach ($this->patterns as $pattern) {
                $matches = array_merge($matches, $pattern->getMatches($this->str));
            }
        }
        return array_values($matches); // Reset keys of the array
    }
    
    /* Public methods (API) */

    public function setTargetString(string $str): void {
        $this->str = $str;
    }

    public function get(): array {
        return $this->getAllMatches();
    }

    public function check(): string {
        return $this->validateAsInput();
    }

    public function checkString(): string {
        return $this->validateAsString();
    }

    public function count(): string {
        return count($this->getAllMatches());
    }

    // public function toRegex(): string {
    //     $this->build();
    //     return $this->regex;
    // }

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

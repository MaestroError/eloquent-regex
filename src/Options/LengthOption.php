<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\AddToPatternTrait;
use Maestroerror\EloquentRegex\Traits\IsOptionalTrait;

class LengthOption implements OptionContract {

    use AddToPatternTrait, IsOptionalTrait;

    private $minLength = null;
    private $maxLength = null;
    private $exactLength = null;

    public function validate(string $input): bool {
        $length = strlen($input);

        if ($this->exactLength !== null && $length !== $this->exactLength) {
            return false;
        }

        if ($this->minLength !== null && $length < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            return false;
        }

        return true;
    }

    public function build(): string {
        if ($this->exactLength !== null) {
            return "{{$this->exactLength}}";
        }

        $min = $this->minLength ?? '';
        $max = $this->maxLength ?? '';

        if ($min === '' && $max !== '') {
            $pattern = "{0,{$max}}";
        } else {
            $pattern = "{{$min},{$max}}";
        }


        if ($this->isOptional && $min === '' && $max === '') {
            $pattern = "(?:.+)?";
        } else if ($this->isOptional) {
            $pattern = "(?:$pattern)?";
        }

        return $pattern;
    }

    public function reset() {
        $this->minLength = null;
        $this->maxLength = null;
        $this->exactLength = null;
        $this->isOptional = false;
        return $this;
    }

    public function description(): string {
        return "Length option with min length: {$this->minLength}, max length: {$this->maxLength}, exact length: {$this->exactLength}";
    }


    // Additional methods
    public function minLength(int $length) {
        $this->minLength = $length;
        $this->exactLength = null; // Reset exact length if min or max length is set
        return $this;
    }

    public function maxLength(int $length) {
        $this->maxLength = $length;
        $this->exactLength = null; // Reset exact length if min or max length is set
        return $this;
    }

    public function exactLength(int $length) {
        $this->exactLength = $length;
        $this->minLength = null;
        $this->maxLength = null;
        return $this;
    }
}

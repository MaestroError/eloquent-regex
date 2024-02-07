<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class LengthOption implements OptionContract {

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

        return $pattern;
    }

    // Option methods
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

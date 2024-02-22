<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class FileOption implements OptionContract {

    use ValidateUsingRegexTrait;

    private $isFile = false;
    private $isDirectory = 0;
    private $fileExtension = null;
    private $validateUsingRegex = true;

    public function validate(string $input): bool {
        if ($this->validateUsingRegex) {
            return $this->validateUsingRegex($input);
        }

        if ($this->isFile) {
            if ($this->fileExtension) {
                if (!preg_match("/\." . preg_quote($this->fileExtension) . "$/", $input)) {
                    return false;
                }
            } elseif (!preg_match("/\.[a-zA-Z0-9]+$/", $input)) {
                return false;
            }
        }

        if ($this->isDirectory) {
            if (substr($input, -1) != '/') {
                return false;
            }
        }

        return true;
    }

    public function build(): string {
        if ($this->isFile) {
            if ($this->fileExtension) {
                return "[A-Za-z0-9\\/:\.\-\\\\]*\." . preg_quote($this->fileExtension);
            } else {
                return "[A-Za-z0-9\\/:\.\-\\\\]*\.[a-zA-Z0-9]+";
            }
        }

        if ($this->isDirectory) {
            return "(?:[a-zA-Z0-9\\/:\-\\\\]+)+";
        }

        return '.*';
    }

    public function isFile(string|null $extension = null) {
        $this->isFile = true;
        $this->fileExtension = $extension;
        return $this;
    }

    public function isDirectory(int $check = 1) {
        $this->isDirectory = $check;
        return $this;
    }
}

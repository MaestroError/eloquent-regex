<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class PathTypeOption implements OptionContract {

    private bool $relativePath = false;
    private bool $absolutePath = false;

    public function validate(string $input): bool {
        if ($this->relativePath) {
            return $this->isRelativePath($input);
        }

        if ($this->absolutePath) {
            return $this->isAbsolutePath($input);
        }

        // If neither relativePath nor absolutePath is specified, validation passes by default.
        return true;
    }

    public function build(): string {
        // This method is not used as the validation is done in PHP and not with regex.
        return "";
    }

    private function isRelativePath(string $path): bool {
        return !preg_match('/^(?:\/|[a-zA-Z]:\\\\)/', $path);
    }

    private function isAbsolutePath(string $path): bool {
        return preg_match('/^(?:\/|[a-zA-Z]:\\\\)/', $path);
    }

    // Option methods
    public function setPathType(string|int $value = 0): self {
        if ($value) {
            if ($value == 1 || $value == "absolute") {
                $this->absolutePath = $value;
            }
            if ($value == 2 || $value == "relative") {
                $this->relativePath = $value;
            }
        }
        return $this;
    }
}

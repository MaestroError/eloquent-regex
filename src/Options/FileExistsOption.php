<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class FileExistsOption implements OptionContract {

    private $fileExists = false;

    public function validate(string $input): bool {

        if ($this->fileExists && !file_exists($input)) {
            return false;
        }

        return true;
    }

    public function build(): string {
        return '';
    }

    public function fileExists() {
        $this->fileExists = true;
        return $this;
    }
}

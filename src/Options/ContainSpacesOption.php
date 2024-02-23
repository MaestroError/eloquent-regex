<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class ContainSpacesOption implements OptionContract {

    private bool $allowSpaces = true;
    private bool $noDoubleSpaces = false;
    private ?int $maxSpaces = null;

    public function validate(string $input): bool {
        if (!$this->allowSpaces && strpos($input, ' ') !== false) {
            return false;
        }

        if ($this->noDoubleSpaces && preg_match('/\s{2,}/', $input)) {
            return false;
        }

        if ($this->maxSpaces !== null && substr_count($input, ' ') > $this->maxSpaces) {
            return false;
        }

        return true;
    }

    public function build(): string {
        // Not used as validation is done in PHP.
        return "";
    }

    public function noSpaces(bool $disallow = true): self {
        $this->allowSpaces = !$disallow;
        return $this;
    }

    public function noDoubleSpaces(bool $disallow = true): self {
        $this->noDoubleSpaces = $disallow;
        return $this;
    }

    public function maxSpaces(int $max): self {
        $this->maxSpaces = $max;
        return $this;
    }
}

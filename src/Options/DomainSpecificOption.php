<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class DomainSpecificOption implements OptionContract {

    private array $allowedDomains = [];
    private array $allowedExtensions = [];

    public function validate(string $input): bool {
        if (empty($this->allowedDomains) && empty($this->allowedExtensions)) {
            return true; // If no specific domains or extensions are set, pass validation by default
        }

        foreach ($this->allowedDomains as $domain) {
            if (preg_match('/' . preg_quote($domain) . '$/', $input)) {
                return true;
            }
        }

        foreach ($this->allowedExtensions as $extension) {
            if (preg_match('/\.' . preg_quote($extension) . '$/', $input)) {
                return true;
            }
        }

        return false;
    }

    public function build(): string {
        // Not used as validation is done in PHP.
        return "";
    }

    public function setAllowedDomains(array|string $domains): self {
        if (is_string($domains)) {
            $domains = explode(",", $domains);
        }
        $this->allowedDomains = $domains;
        return $this;
    }

    public function setAllowedExtensions(array|string $extensions): self {
        if (is_string($extensions)) {
            $extensions = explode(",", $extensions);
        }
        $this->allowedExtensions = $extensions;
        return $this;
    }
}

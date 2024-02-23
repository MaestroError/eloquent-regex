<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class ProtocolOption implements OptionContract {
    
    private array $allowedProtocols = [];

    public function validate(string $input): bool {
        if (empty($this->allowedProtocols)) {
            return true; // If no specific protocols are set, pass validation by default.
        }

        foreach ($this->allowedProtocols as $protocol) {
            if (strpos($input, $protocol . '://') === 0) {
                return true; // The input starts with one of the allowed protocols.
            }
        }

        return false; // None of the allowed protocols matched.
    }

    public function build(): string {
        // This method is not used as the validation is done in PHP and not with regex.
        return "";
    }

    public function onlyProtocol(string|array $protocol): self {
        if (is_array($protocol)) {
            $this->allowedProtocols = $protocol;
        } else {
            $this->allowedProtocols[] = $protocol;
        }
        return $this;
    }

    public function onlyHttp(bool $only = true): self {
        if ($only) {
            $this->allowedProtocols[] = 'http';
        }
        return $this;
    }

    public function onlyHttps(bool $only = true): self {
        if ($only) {
            $this->allowedProtocols[] = 'https';
        }
        return $this;
    }
}

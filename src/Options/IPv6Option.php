<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class IPv6Option implements OptionContract {

    private bool $validate = false;

    public function validate(string $input): bool {
        if($this->validate) {
            return filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
        }
        return true;
    }

    public function build(): string {
        return "";
    }

    // Option methods
    public function validIPv6(bool $check = true) {
        $this->validate = $check;
        return $this;
    }
}

<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Options\LengthOption;


class OptionsMapper {
    public array $optionMethods = [];
    public array $availableOptions = [];

    public function __construct() {
        $this->optionMethods = [
            "minLength" => [LengthOption::class, "minLength"]
        ];

        $this->availableOptions = [
            LengthOption::class,
        ];
    }

    static function GetOptionMethodByName(string $optionName) {
        $obj = new self;
        return $obj->optionMethods[$optionName];
    }

    static function GetAvailableOptions() {
        $obj = new self;
        return $obj->availableOptions;
    }


}
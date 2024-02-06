<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\OptionsMapper;


class OptionsManager {

    private array $options = [];
    private array $usedOptions = [];

    public function __construct() {
        // Fill with available option classes
        $this->options = OptionsMapper::GetAvailableOptions();
    }

    public function getUsedOptions() {
        return $this->usedOptions;
    }

    
    public function buildOptions(array $optionNamesAndValues): void {
        foreach ($optionNamesAndValues as $name => $value) {
            $option = OptionsMapper::GetOptionMethodByName($name);
            $this->processOption($option, $value);
        }
    }

    private function processOption(array $option, mixed $value): void {
        if (isset($this->usedOptions[$option[0]])) {
            $this->usedOptions[$option[0]]->{$option[1]}($value);
        } else {
            $this->usedOptions[$option[0]] = (new $option[0])->{$option[1]}($value);
        }
    }
}
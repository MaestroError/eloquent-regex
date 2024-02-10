<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\OptionsMapper;

/**
 * Class OptionsManager
 * 
 * Manages the creation and configuration of various regex options.
 * This class is responsible for building option objects based on specified names and values,
 * and keeping track of the options that have been used.
 */
class OptionsManager {

    /**
     * @var array Holds instances of options that have been used.
     */
    private array $usedOptions = [];

    /**
     * Returns an array of options that have been used.
     *
     * @return array The array of used option instances.
     */
    public function getUsedOptions(): array {
        return $this->usedOptions;
    }

    /**
     * Builds option instances based on provided names and values.
     * For each option name and value, it identifies the corresponding class and method
     * to create and configure the option instance.
     *
     * @param array $optionNamesAndValues Associative array of option names and their values.
     */
    public function buildOptions(array $optionNamesAndValues): void {
        foreach ($optionNamesAndValues as $name => $value) {
            $option = OptionsMapper::GetOptionMethodByName($name);
            $this->processOption($option, $value);
        }
    }

    /**
     * Processes an individual option by either updating an existing instance or creating a new one.
     * It calls the specific method of the option class to set its value.
     *
     * @param array $option The array containing the class and method name for the option.
     * @param mixed $value The value to set for the option.
     */
    private function processOption(array $option, mixed $value): void {
        if (isset($this->usedOptions[$option[0]])) {
            // If the option instance already exists, update it with the new method and value.
            $this->usedOptions[$option[0]]->{$option[1]}($value);
        } else {
            // If the option instance does not exist, create it, call the method and set the value (arg).
            $this->usedOptions[$option[0]] = (new $option[0])->{$option[1]}($value);
        }
    }
}

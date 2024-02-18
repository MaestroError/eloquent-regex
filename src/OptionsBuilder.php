<?php

namespace Maestroerror\EloquentRegex;

/**
 * Class OptionsBuilder
 *
 * Dynamically builds regex options based on method calls.
 * This class allows setting various regex options using method calls that are dynamically
 * mapped to specific option classes and their methods in OptionsMapper.
 */
class OptionsBuilder {

    protected OptionsManager $manager;

    /**
     * @var array An associative array to store option values keyed by their names.
     */
    private array $options = [];

    /**
     * Constructor.
     *
     * Initializes the OptionsManager instance used for managing options.
     */
    public function __construct() {
        $this->manager = new OptionsManager();
    }

    /**
     * Magic method to handle dynamic method calls for setting options.
     *
     * This method intercepts calls to methods that are not explicitly defined in this class
     * and maps them to corresponding option methods defined in the OptionsMapper.
     *
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @return $this Allows for method chaining.
     */
    public function __call($name, $arguments) {
        // Get the option class and method from the OptionsMapper
        if (OptionsMapper::GetOptionMethodByName($name)) {
            $this->options[$name] = $arguments[0];
        }

        return $this;
    }

    /**
     * Retrieves the options set by the dynamic method calls.
     *
     * @return array An associative array of options and their set values.
     */
    public function getOptions(): array {
        return $this->options;
    }
}

# EloquentRegex Structure

EloquentRegex offers a fluent and intuitive interface for Laravel developers to construct and execute regular expressions (regex) with ease. This document outlines the core components and functionalities of the EloquentRegex package, including ready-to-use patterns, custom pattern creation, and the use of options for added assertions and filtering. The primary purpose of the document is to clarify the inner workings and help you to understand the package better.

### Table of Contents

- [EloquentRegex Structure](#eloquentregex-structure)
  - [Ready-to-Use Patterns](#ready-to-use-patterns)
  - [Custom Patterns](#custom-patterns)
    - [Starting Points](#starting-points)
  - [Options](#options)
    - [Applying Options](#applying-options)
    - [Options as Filters](#options-as-filters)
    - [Options in Custom Patterns](#options-in-custom-patterns)
    - [Regex Flags](#regex-flags)
  - [Usage Structure](#usage-structure)
- [Conclusion](#conclusion)

## Ready-to-Use Patterns

The inclusion of ready-to-use patterns for common formats like Email, URL, and IP addresses significantly streamlines validation tasks. These patterns are a testament to EloquentRegex's goal of providing developers with a toolkit that simplifies common regex tasks, making code more readable and maintainable. By encapsulating the complexity of regex syntax for these common use cases, EloquentRegex enables developers to perform validations and extractions without needing to manually craft and test these patterns. These patterns can be used directly with minimal setup:

```php
EloquentRegex::source("test@example.com")->email()->check();
```

They are defined in `src\Patterns` and I tried a lot to make pattern creation process as easy as possible. Here you can check example pattern:

```php
<?php

namespace Maestroerror\EloquentRegex\Patterns;

use Maestroerror\EloquentRegex\Patterns\BasePattern;
use Maestroerror\EloquentRegex\Traits\Pattern;

class EmailPattern extends BasePattern {

    use Pattern;

    // Regex
    protected string $pattern = "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}";

    // Method name, which will be used to apply this pattern
    public static string $name = "email";

    // Available arguments (option names)
    public static array $args = [
        "maxLength", // First argument
        "onlyDomains", // Second argument
        "onlyExtensions", // Third argument...
    ];

    // Options applied by default
    public static array $defaultOptions = [
        "minLength" => 5
    ];
}

```

All available patterns are registered in `src\Builder.php`, inside `$patterns` property

## Custom Patterns

_Custom patterns allow for detailed and specific regex definitions._

The ability to create custom patterns using methods is central to EloquentRegex's value proposition. This feature leverages a fluent API, allowing developers to construct complex regex expressions through a series of method calls. Each method call builds upon the previous one, enabling the construction of regex patterns in a piecewise, understandable manner. This approach not only makes the creation of regex patterns more intuitive but also enhances code readability by making the patterns' purposes and structures clear at a glance.

```php
EloquentRegex::start("#hello #world This is a #test")->hash()->text()->get();
```

This functionality mainly depends on the `src\Patterns\BuilderPattern.php`, which technichally is one of the patterns in the system, just haven't predefined regex and allows you to build regex pattern using the chainable methods.

### Starting Points

Custom pattern can be initiated with `start` or `customPattern` methods, which are interchangeable and mark the beginning of a custom pattern creation. The `source` and the `string` methods are starting point for ready-to-use patterns, while it returns the main `Builder` class, you can "switch" to the custom pattern from it, of course, if you preffer syntax like this:

```php
EloquentRegex::source("#hello #world This is a #test")->start()->hash()->text()->end()->get(); // end() method is optional here
```

## Options

Options provide a powerful way to fine-tune patterns. They can be specific to the pattern or applied globally as extra assertions. Patterns define their arguments for easy option application.

It is quiet easy to add a new option class, mainly it needs 2 methods `validate` and `build`. Validate method is used for validation and build method is optional for cases, where option uses regex for validation (Yes, some options are validating input using PHP methods, some Regex pattern and some both, it depends on the Option). Also, option classes need the "option methods", which should be registered as separated options in the `src\OptionsMapper.php` class. For example:

```php
<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;
use Maestroerror\EloquentRegex\Traits\ValidateUsingRegexTrait;

class FileOption implements OptionContract {

    use ValidateUsingRegexTrait;

    private $isFile = false;
    private $isDirectory = 0;
    private $fileExtension = null;
    private $validateUsingRegex = true;

    // Validates input using regex if "validateUsingRegex" is true, or php blocks otherwise
    public function validate(string $input): bool {
        if ($this->validateUsingRegex) {
            return $this->validateUsingRegex($input);
        }

        if ($this->isFile) {
            if ($this->fileExtension) {
                if (!preg_match("/\." . preg_quote($this->fileExtension) . "$/", $input)) {
                    return false;
                }
            } elseif (!preg_match("/\.[a-zA-Z0-9]+$/", $input)) {
                return false;
            }
        }

        if ($this->isDirectory) {
            if (substr($input, -1) != '/') {
                return false;
            }
        }

        return true;
    }

    // Builds regex pattern
    public function build(): string {
        if ($this->isFile) {
            if ($this->fileExtension) {
                return "[A-Za-z0-9\\/:\.\-\\\\]*\." . preg_quote($this->fileExtension);
            } else {
                return "[A-Za-z0-9\\/:\.\-\\\\]*\.[a-zA-Z0-9]+";
            }
        }

        if ($this->isDirectory) {
            return "(?:[a-zA-Z0-9\\/:\-\\\\]+)+";
        }

        return '.*';
    }

    // "option methods":

    public function isFile(string|null $extension = null) {
        $this->isFile = true;
        $this->fileExtension = $extension;
        return $this;
    }

    public function isDirectory(int $check = 1) {
        $this->isDirectory = $check;
        return $this;
    }
}
```

Later, the "option methods" are registered in OptionsMapper class:

```php
/**
 * @var array Mapping of option names to their corresponding class and method.
 */
public static array $optionMethods = [
    // Other options...
    "isFile" => [FileOption::class, "isFile"],
    "isDirectory" => [FileOption::class, "isDirectory"],
    // Other options...
];
```

_Note: option methods **should** have only one argument._

### Applying Options

- Via Arguments: Directly passing parameters defined by the pattern class in $args property.

```php
EloquentRegex::source("StrongP@ssw0rd")->password(8, 1, 1, 1)->check();
```

- Via Callback: Using a closure to apply multiple options dynamically (recomended way).

```php
EloquentRegex::source("StrongP@ssw0rd")->password(function($pattern) {
    return $pattern->minLength(8)->minUppercase(1)->minNumbers(1)->minSpecialChars(1);
})->check();
```

- Via Array: Applying options using an associative array.

```php
EloquentRegex::source("test@example.com")->email(['onlyExtensions' => ['com', 'org']])->check(); // true
```

_Note: argument includes pattern-specific option in predefined manner, but while using **array** or **callback** you can apply **any option** to **any pattern**_

### Options as Filters

Options can also act as filters during extraction (`get()`), ensuring only matches that meet specific criteria are returned.

```php
EloquentRegex::source("Visa: 4111 1111 1111 1111, MasterCard: 5500 0000 0000 0004, Amex: 3400 000000 00009")
    ->creditCardNumber("visa, amex")->get();
// Returns only Visa and Amex card numbers

```

### Options in Custom Patterns

Sure! You can apply option to your custom pattern using the `end(callback|array $config)` method:

```php
EloquentRegex::customPattern("Users: user_123, JohnDoe_99")
    ->alphanumeric()
    ->underscore()
    ->digitsRange(0, 5)
    ->end(["maxLength" => 8]) // Applied maxLength option
    ->get(); // Returns array including "user_123" within
```

### Regex Flags

Regex flags are applied outside the options scope through easy-to-use methods, allowing for global pattern modifiers like Unicode support.

```php
// Regex flags can be applied only after the end() method
EloquentRegex::start("მზადაა #1 ✔️ და #2 ✔️")->wordCharRange(0, 2)->end()->asUnicode()->get();
```

## Usage structure

Here you can check the usage structure:

```
[Initiator][Pattern][?Optional][Action]
```

- **Initiator** - Sets target string and returns `Builder` for ready-to-use patterns or `BuilderPattern` for building custom patterns (ex: `EloquentRegex::source`)
- **Pattern** - Method for ready to use pattern like `email`, `url`, `filePath` and etc. Or custom pattern created using the `BuilderPattern` methods.
- **?Optional** - Any optional methods like: regex expression flags and `end` method
- **Action** - Final method which performs some action like: `get`, `check`, `toRegex` and etc.

_Note: Action methods have replicas in `BuilderPattern` to ensure that `end` method remains optional_

# Conclusion

EloquentRegex simplifies the creation and execution of regular expressions in Laravel applications. Through its intuitive API, developers can quickly implement complex regex operations with precision and flexibility. Whether utilizing ready-to-use patterns for common tasks or crafting custom solutions with dynamic options, EloquentRegex enhances productivity and code clarity.

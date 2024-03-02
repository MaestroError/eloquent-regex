# eloquent-regex

EloquentRegex brings the simplicity and elegance to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications.

### Table of Contents

- [Overview](#overview)
  - [Key Features](#key-features)
  - [Getting Started](#getting-started)
- [Basic Usage](#basic-usage)
  - [Ready-to-Use Patterns](#ready-to-use-patterns)
  - [Custom Patterns](#custom-patterns)
    - [Creating a Custom Pattern](#creating-a-custom-pattern)
  - [Applying Quantifiers](#applying-quantifiers)
    - [Optional Elements](#optional-elements)
    - [Specifying a Range](#specifying-a-range)
    - [One or More](#one-or-more)
    - [Zero or More](#zero-or-more)
    - [Exact Number](#exact-number)
    - [Custom Character Sets and Groups](#to-custom-character-sets-and-groups)
    - [Quantifier Values](#quantifier-values)
- [Advanced usage](#advanced-usage)
  - [Options](#options)
    - [Options as extra assertions](#options-as-extra-assertions)
    - [Options as filters](#options-as-filters)
    - [Options list](#options-list)
    - [Options in custom patterns](#options-in-custom-patterns)
  - [Regex Flags](#regex-flags)
    - [Case-Insensitive Matching](#case-Insensitive-matching)
    - [Multiline Matching](#multiline-matching)
    - [Single-Line Mode](#single-line-mode)
    - [Unicode Character Matching](#unicode-character-matching)
  - [Groups](#groups)
    - [Capturing Groups](#capturing-groups)
    - [Non-Capturing Groups](#non-capturing-groups)
    - [Groups with quantifier](#groups-with-quantifier)

# Overview

#### Dreaming of a world where regex doesn't feel like a rocket science? 😄🚀

Regular expressions (regex) are powerful, no doubt. They're the Swiss Army knife for string manipulation and validation. But let's be honest, they can also be a bit of a headache. The syntax is dense, and a tiny mistake can throw everything off. It's like they're designed to be as intimidating as possible, especially when you're just trying to validate an email address!

Enter **EloquentRegex**. Our goal is to make working with regex in Laravel not just bearable, but actually enjoyable. Yes, you heard that right—**enjoyable**!

EloquentRegex is a PHP/Laravel package that offers a fluent, intuitive interface for constructing and executing regular expressions. Whether you need to validate user input, parse text, or extract specific information from strings, EloquentRegex makes it simple and straightforward. For example:

```php
$isValid = EloquentRegex::source('test@example.com')->email()->check();
```

## Key Features

- **Ready-to-Use Patterns**: Common patterns like emails, URLs, and IP addresses are pre-defined and ready to go. Just a few keystrokes and you're validating.
- **Custom Patterns Made Easy**: Build your own regex patterns with an easy-to-use, fluent interface. Say hello to readable regex!
- **Options and Filters**: Tailor your regex operations with options and filters for precision matching. It's like having a regex wizard at your fingertips.
- **Laravel Integration**: Seamlessly integrates with your Laravel projects, leveraging Laravel's elegant syntax and features.

_For more details about package and it's inner workings check out [STRUCTURE.md](https://github.com/MaestroError/eloquent-regex/blob/update-documentation-and-add-advanced-usage-section/STRUCTURE.md) file._

## Getting Started

Simply install the package via Composer, and you're ready to take the pain out of regex in your PHP/Laravel applications. Follow our quick start guide below to dive in.

```bash
composer require maestroerror/eloquent-regex
```

Later, here will be added our quick start guide.

Remember, regex doesn't have to be a source of frustration. With EloquentRegex, you're on your way to becoming a regex master, all while writing cleaner, more maintainable Laravel code.

# Basic Usage

EloquentRegex simplifies regular expressions in Laravel, making it easy to validate data, search text, and extract information. This section introduces the basic usage of EloquentRegex, including leveraging ready-to-use patterns and creating custom patterns.

First of all, you need to include EloquentRegex class.

```php
use Maestroerror\EloquentRegex\EloquentRegex;
```

**Recomended for Laravel:**

```php
use Maestroerror\EloquentRegex\Facades\EloquentRegex;
```

Usage structure is very similar to Laravel's Eloquent ORM, check this out:

```
[Initiator][Pattern][?Optional][Action]
```

Let's break it down:

- **_Initiator_** sets the target string

```php
EloquentRegex::source($yourString);
```

- **_Pattern_** Could be method for one of the ready-to-use patterns or your custom pattern (we will talk about custom pattern later). Let's keep the example simple and add url pattern:

```php
EloquentRegex::source($yourString)->url();
```

_Note: **?Optional** methods mostly are the expression flags, we will talk about them in next sections_

- **_Action_** is the execution method, check the example:

```php
// get() will return array/collection of URLs if any found in $yourString
EloquentRegex::source($yourString)->url()->get();

// check() will return true if $yourString exactly matches the pattern
// In this case, if $yourString is URL, false otherwise
EloquentRegex::source($yourString)->url()->check();

// checkString() will return true if $yourString contains any matches of the pattern
// In this case, if $yourString contains minimum 1 URL, false otherwise
EloquentRegex::source($yourString)->url()->checkString();

// count() will return count of matches, in this case, amount of URLs in $yourString
EloquentRegex::source($yourString)->url()->count();

// toRegex() will return string - raw regex pattern (without options applied)
EloquentRegex::source($yourString)->url()->toRegex();
```

## Ready-to-Use Patterns

EloquentRegex comes with a set of predefined patterns for common validation/extraction tasks. These patterns are designed to be straightforward and easy to use, requiring minimal effort to implement.

We have different ways to apply options, but the most common and easy way is to pass them as arguments. Note that all arguments are optional.

Here you can check all available methods of ready-to-use patterns and their arguments:

```php
public function textOrNumbers(
  int $minLength,
  int $maxLength,
  int $minUppercase,
  int $minLowercase,
  int $minNumbers,
  int $maxNumbers
);
```

```php
// $onlyDomains & $onlyExtensions array
// or string separated by comma `"example.org,example.com"`
public function email(
  int $maxLength,
  array|string $onlyDomains,
  array|string $onlyExtensions
);
```

```php
public function url(array|string $onlyProtocol);
```

```php
// $onlyDomains & $onlyExtensions array or string separated by comma "org,com"
public function domainName(
  int $maxLength,
  array|string $onlyDomains,
  array|string $onlyExtensions
);
```

```php
public function date();
```

```php
public function time();
```

```php
public function ipAddress();
```

```php
public function ipv6Address();
```

```php
// $cardTypes string separated by comma "visa, amex"
public function creditCardNumber(string $cardTypes);
```

```php
// $countryCode should passed without "+" sign: phone("1"), phone("995")
public function phone(string $countryCode);
```

```php
public function username(int $maxLength);
```

```php
public function password(
  int $minLength,
  int $minUppercase,
  int $minNumbers,
  int $minSpecialChars
);
```

```php
// $restrictTags & $onlyTags array or string
// separated by comma `"script, style"`.
// It isn't recomended to use both option simultaneously
public function htmlTag(array|string $restrictTags, array|string $onlyTags);
```

```php
// $specificCurrencies array of currency symbols or string separated by comma "$, ₾"
public function currency(
  int $minDigits,
  int $maxDigits,
  array|string $specificCurrencies
);
```

```php
// $pathType allowed values: "absolute" & "relative"
public function filePath(
  int $isDirectory,
  bool $isFile,
  bool $fileExists,
  string $pathType
);
```

```php
public function filePathWin(
  int $isDirectory,
  bool $isFile,
  bool $fileExists
);
```

Didn't it cover all your needs? Let's take a look to the custom patterns section.

## Custom Patterns

For scenarios where predefined patterns do not suffice, EloquentRegex allows you to define custom patterns using the start or customPattern methods as initiator:

```php
EloquentRegex::start($yourString);
// Or
EloquentRegex::customPattern($yourString);
```

_Note: They does the exaclty same thing, you can use your favorite one_

### Creating a Custom Pattern

You can start building a custom pattern to match a specific string format, such as a custom ID format that starts with letters followed by digits:

```php
$result = EloquentRegex::start('ID123456')
            ->literal('ID')
            ->digitsRange(1, 10)
            ->check();

if ($result) {
    echo "The string matches the custom ID format!";
} else {
    echo "The string does not match the custom ID format.";
}

```

_Note: You can use `EloquentRegex::builder()->pattern()` if you need just build a regex without source string_

Custom pattern builder supports a wide range of character classes and all special chars. Also, `literal` or `exact` method could be used to match exact string you need, or `char` method could be used to match exact character. The full list of pattern builder methods is comming soon. Before that, you can check this files out:

- [Character Classes](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/CharacterClassesTrait.php)
- [Special characters](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/SpecificCharsTrait.php)
- [Groups](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/GroupsTrait.php)
- [Anchors](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/AnchorsTrait.php)

## Applying Quantifiers

Quantifiers in regular expressions are symbols or sets of symbols that specify how many instances of a character, group, or character class must be present in the input for a match to be found. EloquentRegex enhances the way quantifiers are used, making it simpler and more intuitive to define the frequency of pattern occurrences.

### Optional Elements

To make an element optional, use '?'. This matches zero or one occurrence of the preceding element (`dash` in this example).

```php
// Matches a string that may or may not contain a dash
$result = EloquentRegex::start($yourString)->exact("123")->dash('?')->exact("456")->check();
// Result would be true in both cases of $yourString: "123456" & "123-456"
```

### Specifying a Range

For specifying a range of occurrences, use a string with two numbers separated by a comma '2,5'. This matches the preceding element at least and at most the specified times.

```php
// Matches a string with 2 to 5 spaces
$result = EloquentRegex::start($yourString)->text()->space('2,5')->digits()->check();
// Result: the "someText  234" would return true, but the "someText 234" false
```

### One or More

To match one or more occurrences of an element, use '+', '1+', '1>' or 'oneOrMore'. This ensures the element appears at least once.

```php
// Matches strings with one or more backslashes
$result = EloquentRegex::start("\\\\")->backslash('1+')->check();
// Result: true (if one or more backslashes are found)
```

### Zero or More

The '0+' quantifier matches zero or more occurrences of the preceding element.

```php
// Matches strings with zero or more forward slashes
$result = EloquentRegex::start($yourString)->alphanumeric()->dot('0+')->check();
// Result would be true in both cases of $yourString: "test258..." & "test"
```

### Exact Number

To match an exact number of occurrences, directly specify the number.

```php
// Matches strings with exactly 2 underscores
$result = EloquentRegex::start($yourString)->digits()->underscore('2')->digits()->check();
// Result would be true in cases $yourString: "1235__158", but "1235___158" and "1235_158" will be false

```

### To Custom Character Sets and groups

You can apply quantifiers to custom character sets and groups as second argument after the callback, matching a specific number of occurrences.

```php
// Matches strings with exactly 3 periods or colons
$regex = EloquentRegex::builder()->start()
    ->charSet(function ($pattern) {
        $pattern->period()->colon();
    }, '3')->toRegex();
// Result: ([\.\:]){3}
```

### Quantifier values

In [Special characters](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/SpecificCharsTrait.php)
and [Groups](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/GroupsTrait.php) - nearly all methods allowing quantifiers with values:

- Zero or More = `"zeroOrMore"`, `"0>"`, `"0+"`, `"*"`
- One or More = `"oneOrMore"`, `"1>"`, `"1+"`, `"+"`
- Optional (Zero or One) = `"optional"`, `"?"`, `"|"`
- exact amount = `2`, `"5"`
- range = `"0,5"`

Example: `->literal("hello world", false, "1+")`

But
[Character Classes](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/CharacterClassesTrait.php)
have different approach, lets take `digits` as example:

```php
// By defualt it is set as One or More
EloquentRegex::start($yourString)->digits();

// You can completly remove quantifier by passing 0 as first argument
EloquentRegex::start($yourString)->digits(0);

// You can specify exact amount of digit by passing int
EloquentRegex::start($yourString)->digits(5);

// You can specify range of digits by adding "Range" to the method
EloquentRegex::start($yourString)->digitsRange(1, 5); // Matches from 1 to 5 digits
```

# Advanced usage

As you become more comfortable with the basics of EloquentRegex, you might find yourself needing to tackle more complex string manipulation challenges. The "Advanced Usage" section is designed to take your skills to the next level.

Whether you're dealing with intricate string formats, dynamic pattern requirements, or simply looking to optimize your regex operations for performance and clarity, this section will guide you through the advanced features of EloquentRegex. You'll learn how to leverage the full power of this package to make your Laravel application's text processing as efficient and effective as possible.

## Options

EloquentRegex provides a flexible system for applying options to your patterns. These options can serve as extra assertions to refine pattern matching or act as filters to select only specific matches. There are three main ways to apply options: directly as arguments, through a callback, and via an associative array.

#### Direct Arguments

Pass options directly as arguments to pattern methods for straightforward use cases.

```php
EloquentRegex::source("StrongP@ssw0rd")->password(8, 1, 1, 1)->check();
```

#### Callback

A callback offers the most flexibility, allowing any option to be applied to any pattern. Also, It's the recommended approach for complex configurations to keep your code simple and readible.

```php
EloquentRegex::source("StrongP@ssw0rd")->password(function($pattern) {
  $pattern->minLength(8)->minUppercase(1)->minNumbers(1)->minSpecialChars(1);
})->check();

```

#### Associative Array

Options can also be specified using an associative array, providing a clear and concise way to configure multiple options at once.

```php
EloquentRegex::source("StrongP@ssw0rd")
->password([
  "minLength" => 8,
  "minUppercase" => 1,
  "minNumbers" => 1,
  "minSpecialChars" => 1,
])->check();
```

_Note: To keep it simple - all option methods have exactly one argument_

### Options as extra assertions

Options can make extra assertions (while using `check` or `checkString` methods) beyond the basic pattern match, ensuring that matches meet specific criteria.

```php
// The "filePath" pattern matches any file path
// While "pathType" option Asserts the file path is an absolute
EloquentRegex::source("/var/www/html/index.php")
  ->filePath(["pathType" => "absolute"])
  ->check();
```

### Options as filters

In some cases (While using `get` method), options serve to filter the results obtained from a pattern match, allowing only certain matches to pass through.

```php
$string = "Visa: 4111 1111 1111 1111, MasterCard: 5500 0000 0000 0004, Amex: 3400 000000 00009";
// The "creditCardNumber" pattern matches any credit card number
// While "cardTypes" (#1 argument) option filters and returns only VISA and AMEX accordingly
$cards = EloquentRegex::source($string)->creditCardNumber("visa, amex")->get();
```

### Options in custom patterns

Using custom pattern is greate way to cover specific use cases, but there can be a moment when you need extra assertion or filter for you matches. While the `end()` method if optional by default, if you need to apply the **Options** to yor custom pattern, you should pass array or callback to the `end()` method:

```php
// example of using end() method with config array
EloquentRegex::customPattern("Users: user_123, JohnDoe_99")
  ->alphanumeric()
  ->underscore()
  ->digitsRange(0, 2)
  ->end(["minLength" => 10])
  ->checkString();

// example of using end() method with callback
$check = EloquentRegex::customPattern("Users: user_123, JohnDoe_99")
  ->alphanumeric()
  ->underscore()
  ->digits()
  ->end(function ($p) {
      $p->minLength(10)->maxDigits(2);
  })
  ->checkString();
```

### Options list

Below is a list of all available options for now. As previously mentioned, options can be applied to any pattern using either a callback or an array.

While this flexibility allows you to tailor your regex patterns precisely, it's important to understand that some options more specific and some are more global in terms of appliance. Choosing the right option depends on the specifics of your use case.

I grouped options by the classes behind them to make their purpose more clear:

- Length options

```php
public function minLength(int $minLength);
public function maxLength(int $maxLength);
public function length(int $exactLength);
```

- Numbers options

```php
public function minNumbers(int $minDigits);
public function maxNumbers(int $maxDigits);
public function minDigits(int $minDigits);
public function maxDigits(int $maxDigits);
public function numberAmount(int $exactAmountOfDigits);
```

- Character options

```php
public function allowChars(array $characters);
public function excludeChars(array $characters);
public function minUppercase(int $minAmount);
public function minLowercase(int $maxAmount);
public function minSpecialChars(int $minAmount);
public function maxSpecialChars(int $maxAmount);
public function noSpecialChars(bool $disable = true);
public function onlyLowercase(bool $only = true);
public function onlyUppercase(bool $only = true);
```

_Note: Options having default value can be used without arguments (`noSpecialChars()`) in callback, but it needs argument while using array `["noSpecialChars" => true]`_

- IPv6 option

```php
public function validIPv6();
```

- File options

```php
public function isFile(string|null $extension = null);
public function isDirectory(int $check = 1);
```

- File Exists option

```php
public function fileExists(bool $check = true);
```

- Specific Currencies options

```php
public function specificCurrencies(array|string $currencies);
public function onlyUSD($only = true);
public function onlyEUR($only = true);
public function onlyGBP($only = true);
public function onlyGEL($only = true);
```

- Path type option

```php
// Allowed values int 1; string "absolute" | int 2; string "relative";
public function pathType(string|int $value = 0);
```

- Country Code option

```php
public function countryCode(string $code);
```

- Space options

```php
public function noSpaces(bool $disallow = true);
public function noDoubleSpaces(bool $disallow = true);
public function maxSpaces(int $max);
```

- Domain specific options

```php
public function onlyDomains(array|string $domains);
public function onlyExtensions(array|string $extensions);
```

- Protocol options

```php
public function onlyProtocol(string|array $protocol);
public function onlyHttp(bool $only = true);
public function onlyHttps(bool $only = true);
```

- Card Type options

```php
public function onlyVisa(bool $only = true);
public function onlyMasterCard(bool $only = true);
public function onlyAmex(bool $only = true);
public function cardTypes(string $cardTypes);
```

- onlyAlphanumeric option

```php
public function onlyAlphanumeric(bool $only = true);
```

- HTML tag options

```php
public function onlyTags(array|string $tags);
public function restrictTags(array|string $tags);
```

## Regex Flags

Regex flags are special tokens that modify the behavior of regular expressions, allowing for more flexible and powerful pattern matching. In EloquentRegex, applying regex flags to your patterns enables specialized matching behaviors such as case-insensitive searches, multiline matching, single-line mode, and support for Unicode characters. Let's explore how to apply these flags using examples.

### Case-Insensitive Matching

Sometimes, the case of letters in a string should not affect the match. To achieve case-insensitive matching, use the asCaseInsensitive() flag.

```php
// When $string can be "Example@Email.COM", or "EXAMPLE@Email.com", or "example@EMAIL.COM" and etc.
$checkWithFlag = EloquentRegex::source($string)
                ->start()
                ->exact("example")
                ->character("@")
                ->exact("email.com")
                ->end()
                ->asCaseInsensitive()->check();

// With the case-insensitive flag, the match succeeds.
expect($checkWithFlag)->toBeTrue();
```

### Multiline Matching

The multiline flag allows the start (^) and end ($) anchors to match the start and end of lines within a string, rather than the entire string.

**Example: Matching Dates Across Multiple Lines using check() method**

```php
$string = "2024-01-30\n2024-02-15\n2024-11-30";
$matches = EloquentRegex::source($string)
            ->start()
            ->digits(4)->dash()
            ->digits(2)->dash()
            ->digits(2)
            ->end() // Here you can apply options
            ->asMultiline()->check();
expect($matches)->toBeTrue();
```

_Note: if you need to check if string contains a date, using checkString() method is enough. In this example we are checking that every line is exactly the date._

### Single-Line Mode

In single-line mode, the dot (.) matches every character, including newline characters, allowing patterns to match across lines.

**Example: Matching Text Across Lines as a Single Line String using check() method**

```php
$string = "Check out\n this site:";
$check = EloquentRegex::source($string)
          ->start()->anyChars()->character(":")->end()->asSingleline()
          ->check();
expect($check)->toBeTrue(); // Matches across lines due to the single-line flag.

```

### Unicode Character Matching

When working with texts containing Unicode characters, the Unicode flag ensures that character classes such as \w (word characters - `wordChars` method) and \d (digits - `digits` method) correctly match Unicode characters.

**Example: Matching Text with Unicode Characters**

```php
$string = "მზადაა #1 ✔️ და #2 ✔️";
$matches = EloquentRegex::source($string)
            ->start()->wordCharsRange(0, 2)->end()->asUnicode()->get();
expect($matches)->toContain('და'); // Matches Unicode characters with the Unicode flag.

```

## Groups

EloquentRegex simplifies the process of creating both capturing and non-capturing groups, allowing you to organize your regex patterns into logical sections and apply quantifiers or assertions to these groups as a whole.

### Capturing Groups

Capturing groups are used to group part of a pattern together and capture the matching text for later use. Note that it returs array/collection with different structure while using with get:

```php
// Matching a date format across multiple lines without capturing the groups
$result = EloquentRegex::start("2024-01-30, 2023-02-20")
->group(function($pattern) {
    $pattern->digits(4); // Year
})->dash()
->group(function($pattern) {
    $pattern->digits(2); // Month
})->dash()
->group(function($pattern) {
    $pattern->digits(2); // Day
})->end(["excludeChars" => ["4"]])
->get();

/**
 * After excluding "4" character, it filters out
 * the "2024-01-30" match and returns only "2023-02-20"
 * with it's capture groups, so that you get this array:
 * [
 *      "result" => "2023-02-20",
 *      "groups" => [
 *          "2023",
 *          "02",
 *          "20"
 *      ],
 * ]
 */
```

### Non-Capturing Groups

Non-capturing groups organize patterns logically without capturing the matched text.

```php
// Reproduces an 'alt' html property pattern fron HSA
$regex = EloquentRegex::source('alt="something"')
    ->exact("alt=")
    ->nonCapturingGroup(function ($pattern) {
        $pattern->doubleQuote()->orPattern(function ($pattern) {
            $pattern->singleQuote();
        });
    })->check(); // True; Regex: alt\=(?:\"|')
```

### Groups with quantifier

Both group methods are supporting quantifier as second argument. Quantifiers can be applied with exact same logic as on special character methods.

```php
EloquentRegex::start("345-45, 125-787, 344643")
  ->nonCapturingGroup(function ($pattern) {
    $pattern->digits()->dash()->digits();
  }, '+') // Using "+" to match One Or More of this group
  ->get();
// It returns array: ["345-45", "125-787"]
```

---

##### To Do

- Return captured groups while using `group()` method with `get()`.✔️
- Add options for new patterns:
  - Add `contains` and `notContains` options
  - usernameLength: Set minimum and maximum length for the username part of the email.
  - dateFormat, timeFormat: Specify the format of date and time (e.g., MM-DD-YYYY, HH:MM).
- Consider to register Patterns like options using key (name) => value (class) pairs (check performance) ✔️ (_No significant change before 50+ patterns_)
- Return collection on get method if laravel is available.
- Implement usage of named groups: `/(?P<year>\d{4})-(?P<month>\d{2})-(?P<day>\d{2})/`

- Write documentation (add credit for https://regexr.com/ and ChatGPT)
  - Create quick start guide and add in Docs.
  - Add advanced usage section in Docs:
    - Options and Assertions: Detailed explanation of options, how to apply them, and their effects on patterns. ✔️
    - Filters in Extraction: Using options as filters during extraction and the types of filters available. ✔️
    - Options list ✔️
    - Ensure digits / digit behavior. ✔️
    - Regex Flags: Guide on applying regex flags to patterns for specialized matching behavior. ✔️
    - Grouping and Capturing: How to use groups (capturing and non-capturing) and apply quantifiers to them. ✔️
    - Sets
    - orPattern
    - Lookaheads
    - Raw methods
  - Add section in docs for "lazy" method
  - Add sections:
    - Testing and Debugging
    - Credits
    - Contributing
    - FAQs
    - Creating new patterns

##### Coming next

- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Implement recursive pattern creation (Using "RI-321" string to create pattern matching this string)
- Consider to add Postal Code Pattern
- Make options controllable from config or provider (?)
- Make patterns controllable from config or provider (?)
- I should be able to make new pattern using BuilderPattern
- I should be able to make add custom pattern to the existing one using BuilderPattern

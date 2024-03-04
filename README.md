# eloquent-regex

EloquentRegex brings the simplicity and elegance to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications.

```php
EloquentRegex::source('test@example.com')->email()->check();
```

Like what we're doing? Show your support with a quick star, please! ⭐

### Table of Contents

- **[Overview](#overview)**
  - 🔑[Key Features](#key-features)
  - 🧭[Getting Started](#getting-started)
- **[Basic Usage](#basic-usage)**
  - 📑[Ready-to-Use Patterns](#ready-to-use-patterns)
  - 🛠️[Custom Patterns](#custom-patterns)
    - 💠 [Creating a Custom Pattern](#creating-a-custom-pattern)
  - #️⃣[Applying Quantifiers](#applying-quantifiers)
    - 💠 [Optional Elements](#optional-elements)
    - 💠 [Specifying a Range](#specifying-a-range)
    - 💠 [One or More](#one-or-more)
    - 💠 [Zero or More](#zero-or-more)
    - 💠 [Exact Number](#exact-number)
    - 💠 [Custom Character Sets and Groups](#to-custom-character-sets-and-groups)
    - 💠 [Quantifier Values](#quantifier-values)
- **[Advanced usage](#advanced-usage)**
  - ⚙️[Options](#options)
    - 💠 [Options as extra assertions](#options-as-extra-assertions)
    - 💠 [Options as filters](#options-as-filters)
    - 💠 [Options list](#options-list)
    - 💠 [Options in custom patterns](#options-in-custom-patterns)
  - 🚩[Regex Flags](#regex-flags)
    - 💠 [Case-Insensitive Matching](#case-insensitive-matching)
    - 💠 [Multiline Matching](#multiline-matching)
    - 💠 [Single-Line Mode](#single-line-mode)
    - 💠 [Unicode Character Matching](#unicode-character-matching)
- **[Advanced builderPattern methods](#advanced-builderpattern-methods)**
  - 🗃️[Character Sets](#character-sets)
  - 📦[Groups](#groups)
    - 💠 [Capturing Groups](#capturing-groups)
    - 💠 [Non-Capturing Groups](#non-capturing-groups)
    - 💠 [Groups with quantifier](#groups-with-quantifier)
  - ❓[Conditional matching](#conditional-matching)
  - ⚖️[Pattern alternation (orPattern)](#pattern-alternation-orpattern)
  - 🧩[Raw Methods](#raw-methods)
  - 🐌[The Lazy Quantifier Method](#the-lazy-quantifier-method)
- **[Testing and Debugging Your Regex Patterns](#testing-and-debugging-your-regex-patterns)**
- **[Contributing to EloquentRegex](#contributing-to-eloquenttegex)**
- **[Support](#support)**

# Overview

#### Dreaming of a world where regex doesn't feel like a rocket science? 😄🚀

Regular expressions (regex) are powerful, no doubt. They're the Swiss Army knife for string manipulation and validation. But let's be honest, they can also be a bit of a headache. The syntax is dense, and a tiny mistake can throw everything off. It's like they're designed to be as intimidating as possible, especially when you're just trying to validate an email address!

Enter **EloquentRegex**. Our goal is to make working with regex in Laravel not just bearable, but actually enjoyable. Yes, you heard that right—**enjoyable**!

EloquentRegex is a PHP/Laravel package that offers a fluent, intuitive interface for constructing and executing regular expressions. Whether you need to validate user input, parse text, or extract specific information from strings, EloquentRegex makes it simple and straightforward.

**For example:**

```php
$link = 'https://www.example.com/home';
$isValidUrl = EloquentRegex::source($link)->url()->check(); // true
```

**Another:**

```php
$isStrong = EloquentRegex::source("StrongP@ssw0rd")->password(function($options) {
    $options->minLength(8)->minUppercase(1)->minNumbers(1)->minSpecialChars(1);
})->check(); // true
```

**One more** 😄

```php
EloquentRegex::start("#hello #world This is a #test")->hash()->text()->get();
// ['#hello', '#world', '#test']
```

## Key Features🔑

- **Ready-to-Use Patterns**: Common patterns like emails, URLs, and IP addresses are pre-defined and ready to go. Just a few keystrokes and you're validating.
- **Custom Patterns Made Easy**: Build your own regex patterns with an easy-to-use, fluent interface. Say hello to readable regex!
- **Options and Filters**: Tailor your regex operations with options and filters for precision matching. It's like having a regex wizard at your fingertips.
- **Laravel Integration**: Seamlessly integrates with your Laravel projects, leveraging Laravel's elegant syntax and features.

_For more details about package and it's inner workings check out [STRUCTURE.md](https://github.com/MaestroError/eloquent-regex/blob/update-documentation-and-add-advanced-usage-section/STRUCTURE.md) file._

## Getting Started🧭

Simply install the package via Composer, and you're ready to take the pain out of regex in your PHP/Laravel applications. Run for installation:

```bash
composer require maestroerror/eloquent-regex
```

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

- **_Pattern_** Could be method for one of the ready-to-use patterns or your custom pattern (we will talk about custom patterns later). Let's keep the example simple and add url pattern:

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

## Ready-to-Use Patterns📑

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

## Custom Patterns🛠️

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

## Applying Quantifiers#️⃣

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

## Options⚙️

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
public function onlyChars(array $characters);
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

## Regex Flags🚩

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

# Advanced builderPattern methods

In addition to character classes and special character methods, builderPattern has more advanced methods for increasing flexibility and usage scope. Below are described the methods for the builderPattern's advanced usage.

## Character Sets🗃️

In regular expressions, character sets are a fundamental concept that allows you to define a set of characters to match within a single position in the input string. EloquentRegex provides an intuitive way to work with both positive and negative character sets, enhancing the versatility of your patterns.

<!-- No quantifiers allowed inside set, cause it is parsed as symbols, so 0 (int) should be used where quantifier is enabled -->

#### Positive set (only these characters)

A positive character set matches any one of the characters included within the set. It's specified by enclosing the characters in square brackets `[...]`.

**Example: Matching a Specific Number of Character Sets**

```php
// Matches exactly 3 occurrences of periods or colons
EloquentRegex::start(".:.")
->charSet(function ($pattern) {
    $pattern->period()->colon();
}, '3')->check();
// Expected to be true as it matches three instances of either a period or a colon
```

In this example, the character set `[\.\:]` is created to match either a period . or a colon : (In charSet **order** of characters **not matter**). The quantifier '3' is applied outside the set to match exactly three occurrences of **any** of these characters.

#### Negative set (all but not these characters)

A negative character set, denoted by `[^...]`, matches any character that is not listed within the brackets.

**Example: Matching a Specific Number of Negative Character Sets**

```php
// Matches a string containing 2 to 4 characters that are not digits
EloquentRegex::start("abcd")
->negativeCharSet(function ($pattern) {
    // Here, quantifiers inside the set are interpreted as literal symbols
    // Character classes like "digits", "text", and etc. sets default quantifier (+)
    // Hence, '0' is used to disable automatic quantifier addition
    $pattern->digits(0);
}, '2,4')->check();
// Expected to be true as it matches between 2 to 4 non-digit characters
```

#### Note about character classes

When working with character sets in EloquentRegex, it's important to remember that quantifiers are not allowed inside the set itself because they will be interpreted as symbols. To include character classes like "\d" for digits within a set without applying a quantifier to the class itself, you should pass 0 as the first argument where quantifier application is an option. This ensures that the character class is included in the set as intended, without unintended quantification.

_Update: From now, **0 as argument is optional**, because character classes willn't add default "+" quantifier inside the set_

## Groups📦

EloquentRegex simplifies the process of creating both capturing and non-capturing groups, allowing you to organize your regex patterns into logical sections and apply quantifiers or assertions to these groups as a whole.

### Capturing Groups

Capturing groups are used to group part of a pattern together and capture the matching text for later use. Note that it returs array/collection with different structure while using with get:

```php
// Matching a date format with capturing the parts as separated groups
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
 *     [
 *          "result" => "2023-02-20",
 *          "groups" => [
 *              "2023",
 *              "02",
 *              "20"
 *          ],
 *     ]
 * ]
 */
```

### Non-Capturing Groups

Non-capturing groups organize patterns logically without capturing separately the matched text.

```php
// Reproduces an 'alt' html property pattern fron HSA
$regex = EloquentRegex::start('alt="something"')
    ->exact("alt=")
    ->nonCapturingGroup(function ($pattern) {
        $pattern->doubleQuote()->orPattern(function ($pattern) {
            $pattern->singleQuote();
        });
    })->check(); // True; Regex: alt\=(?:\"|')
```

### Groups with quantifier

Both group methods are supporting quantifier as second argument. Quantifiers can be applied with exact same logic as described in [Applying Quantifiers](#applying-quantifiers) section.

```php
EloquentRegex::start("345-45, 125-787, 344643")
  ->nonCapturingGroup(function ($pattern) {
    $pattern->digits()->dash()->digits();
  }, '+') // Using "+" to match One Or More of this group
  ->get();
// It returns array: ["345-45", "125-787"]
```

## Conditional matching❓

Assertion groups allow for conditional matching based on the presence (positive) or absence (negative) of patterns ahead or behind the current match point, without consuming characters from the string, so that anything inside assertion group willn't be added in matches. See examples below:

#### Positive Lookahead and Lookbehind Assertions

_Example: Using lookAhead Assertions_

Matches digits only if they are followed by a 'D'

```php
// Expected to be true as '3' is followed by 'D'
EloquentRegex::start('3D')
->digits()->lookAhead(function($pattern) {
    $pattern->character('D');
})->check();
// While using "get()" method, 'D' doesn't appear in matches
```

_Example: Using lookBehind Assertions_

Matches digits only if they are preceded by a 'P'

```php
// Expected to be true as '3' is preceded by 'P'
EloquentRegex::start('P3')
->negativeLookBehind(function($pattern) {
    $pattern->character('P');
})->digits()->check();
// While using "get()" method, 'P' doesn't appear in matches
```

#### Negative negativeLookAhead and Lookbehind Assertions

Matches digits only if they aren't followed by a '-'

```php
// "3A" returns True
$string = "3A";
// "3-" returns False
$string = "3-";
EloquentRegex::start($string)
->digits()->negativeLookAhead(function($pattern) {
    $pattern->character('-');
})->check();
// While using "get()" method, '-' doesn't appear in matches
```

_Example: Using negativeLookBehind Assertions_

Matches digits only if they aren't preceded by a '-'

```php
// "A3" returns True
$string = "A3";
// "-3" returns False
$string = "-3";
EloquentRegex::start($string)
->negativeLookBehind(function($pattern) {
    $pattern->character('-');
})->digits()->check();
// While using "get()" method, '-' doesn't appear in matches
```

## Pattern alternation (orPattern)⚖️

Sometimes, you might encounter situations where either one pattern or another is acceptable. For instance, when developing EloquentRegex, a key objective was to enable the reproduction of patterns commonly used in [HSA](https://github.com/MaestroError/html-strings-affixer). Consider the `alt` attribute within an HTML tag, which can be followed by either a double " or a single ' quote. This requirement translates into a regex pattern like `alt\=(\"|')`, indicating an exact match for "alt=" followed by either type of quotation mark.

To achieve this with EloquentRegex, you can utilize the `orPattern` method:

```php
EloquentRegex::builder()->start()
    ->exact("alt=")
    ->group(function ($pattern) {
        $pattern->doubleQuote()
        ->orPattern(function ($pattern) {
            $pattern->singleQuote();
        });
    })->toRegex(); // alt\=(\"|')
```

In this example, we precisely match "alt=" using the `exact` method. We then create a group with the `group` method and include `doubleQuote` in group and then `singleQuote` within the orPattern method's callback. This approach ensures the pattern matches either " or '.

The `orPattern` method also accepts a quantifier as its **second argument** (after callback), applying the same [quantifier logic](#quantifier-values) as elsewhere in EloquentRegex. This feature adds another layer of flexibility, allowing you to specify how many times either pattern should be present.

## Raw Methods🧩

When working with regular expressions, there are times you'll need to insert a segment of raw regex directly into your pattern. This might be due to the complexity of the pattern or simply because you're integrating an existing regex snippet. EloquentRegex accommodates this need with specific methods designed to seamlessly integrate raw regex patterns into your larger expressions.

#### Adding Raw Regex Patterns

The `addRawRegex` method allows you to insert any raw regex directly into your pattern. This is particularly useful for incorporating standard regex snippets without modification.

**_Example: Matching a Social Security Number (SSN)_**

```php
// Directly adds a raw regex pattern for an SSN
EloquentRegex::start('123-45-6789')
    ->addRawRegex('\d{3}-\d{2}-\d{4}')
    ->check();
// Expected to match an SSN format '123-45-6789', but not '123456789'
```

This method is straightforward and ensures that your EloquentRegex pattern can accommodate complex requirements with ease.

#### Wrapping Raw Regex in a Non-Capturing Group

Sometimes, you may want to include a raw regex snippet as part of a larger pattern without capturing its match. The `addRawNonCapturingGroup` method wraps the provided raw regex in a non-capturing group, allowing it to participate in the match without affecting the captured groups.

**_Example: Adding Digits Followed by a Specific Letter_**

```php
// Wraps digits in a non-capturing group and expects an 'A' immediately after
EloquentRegex::source('123A')
    ->addRawNonCapturingGroup('\d', "oneOrMore")->exact('A')
    ->check();
// Expected to match '123A' but not 'A123'
```

## The Lazy Quantifier Method🐌

In the world of regular expressions, greediness refers to the tendency of quantifiers to match as much of the input as possible. However, there are scenarios where you want your pattern to match the smallest possible part of the input that satisfies the pattern, a behavior known as "laziness" or "non-greediness". EloquentRegex introduces a straightforward way to apply this concept through the `lazy()` method.

#### How the Lazy Method Works

The `lazy()` method modifies the behavior of quantifiers that follow it in the pattern, making them match as few characters as possible. This is particularly useful when you want to extract specific segments from a larger block of text without capturing unnecessary parts.

**_Example: Extracting "Secret Coded" Messages from Text_**

Consider a situation where you need to extract coded messages enclosed in curly braces and preceded by a specific keyword within a larger text. Using the greedy approach might lead to capturing more text than intended, including text between messages. The `lazy()` method ensures that only the content directly within the braces, following the keyword, is matched.

```php
$text = "Normal text {secret: message one} more text {secret: another hidden text} end";
$matches = EloquentRegex::source($text)
    ->lookBehind(function ($pattern) {
        $pattern->openCurlyBrace()->exact('secret: ');
    })
    ->lazy()->anyChars()
    ->lookAhead(function ($pattern) {
        $pattern->closeCurlyBrace();
    })
    ->get();

// Extracts ['message one', 'another hidden text'] as separate matches
```

In this example, without the `lazy()` method, the pattern might greedily match from the first `{secret: ` to the last `}`, including everything in between as a single match (`message one} more text {secret: another hidden text`). By applying `lazy()`, the pattern instead matches the smallest possible string that satisfies the pattern within each set of curly braces, effectively separating the messages.

#### When to Use the Lazy Method

The `lazy()` method is invaluable when dealing with patterns that include variable-length content, such as strings or blocks of text, where you aim to extract specific, bounded segments. It's especially useful in parsing structured formats embedded within free text, extracting data from templated content, or any scenario where precision is key to separating multiple matches in a larger string.

By making quantifiers lazy, EloquentRegex empowers you to write more precise and effective patterns, ensuring that your matches are exactly as intended, no more and no less.

# Testing and Debugging Your Regex Patterns

Crafting the perfect regex pattern is an art that often requires iteration, testing, and debugging. EloquentRegex provides tools that make this process easier and more intuitive, allowing you to refine your patterns until they match precisely what you need. One of the most useful methods for this purpose is `toRegex()`, which outputs the raw regex pattern. Combined with online tools like [Regexr](https://regexr.com/), you can visualize and debug your patterns in a user-friendly environment.

#### Using the "toRegex" Method

The `toRegex()` method converts your EloquentRegex pattern into a standard regex pattern string. This is particularly useful for debugging purposes or when you need to share your pattern with others who might not be using EloquentRegex.

**Example: Converting an EloquentRegex Custom Pattern to Raw Regex**

```php
$pattern = EloquentRegex::source('your test string here')
    ->start()
    ->wordChars()->whitespace()->digits()
    ->end()
    ->toRegex();

// Now, $pattern contains the raw regex string that you can test or debug further.
```

_Note: `toRegex` doesn't return regex patterns used by the Options_

#### Debugging with Regexr

[Regexr](https://regexr.com/) is a free online tool that allows you to test and debug regex patterns in real-time. It provides a detailed explanation of each part of your regex, matches highlighted in the text, and even a reference guide for regex syntax.

##### How to Use Regexr for Debugging:

1. **Convert Your Pattern:** Use the toRegex() method to convert your EloquentRegex pattern into a raw regex string.
2. **Open Regexr:** Go to https://regexr.com/ in your web browser.
3. **Paste Your Pattern:** Paste the raw regex string into the "Expression" field on Regexr.
4. **Test Your Pattern:** Enter test strings in the "Test String" area to see how your pattern matches. Regexr will highlight matches and provide useful insights and errors if the pattern doesn't work as expected.

_Note: For debuging `get` method, open "Flags" dropdown and mark "global"_

#### Tips for Effective Debugging

- **Start Simple:** Begin with a simplified version of your pattern and gradually add complexity. This helps isolate issues.
- **Use Descriptive Test Strings:** Include a variety of test strings that cover all the scenarios you expect your pattern to handle, as well as edge cases.
- **Pay Attention to Details:** Regex patterns are sensitive to every character. Double-check your symbols, especially those that have special meanings in regex, like `.`, `*`, `?`, etc.
- **Iterate and Refine:** Don't expect to get everything right on the first try. Use the feedback from testing to refine your pattern iteratively.

Testing and debugging are critical steps in ensuring your regex patterns do exactly what you intend. By leveraging the `toRegex()` method and tools like Regexr, you can make this process more manageable and efficient, leading to more accurate and reliable regex implementations in your Laravel applications.

# Contributing to EloquentRegex

We welcome contributions from the community! Whether you're fixing bugs, adding new features, or improving documentation, your help makes EloquentRegex even better for everyone. We follow the classic GitHub contribution flow. Here's how you can get involved:

### Getting Started

1. **Fork the Repository:** Start by forking the EloquentRegex repository to your own GitHub account. This creates a personal copy for you to experiment with.

2. **Clone Your Fork:** Clone your forked repository to your local machine to start making changes. Use the following command, replacing YOUR_USERNAME with your GitHub username:

```bash
git clone https://github.com/YOUR_USERNAME/eloquent-regex.git
```

3. **Set Up Your Environment:** Make sure you have a working Laravel environment to test your changes against. Follow the setup instructions in the project README to get started.

### Making Changes

1. **Create a New Branch:** For each set of changes or new feature, create a new branch in your local repository. This keeps your work organized and separate from the main codebase.

```bash
git checkout -b feature/my-new-feature
```

2. **Make Your Changes:** Implement your fixes, features, or documentation updates. Keep your changes focused and contained to the issue at hand for easier review.

3. **Commit Your Changes:** Once you're satisfied with your work, commit the changes to your branch. Write clear, concise commit messages that explain the changes made.

```bash
git commit -am "Add a brief description of your changes"
```

4. **Push to Your Fork:** Push your branch and changes to your fork on GitHub.

```bash
git push origin feature/my-new-feature
```

### Submitting a Pull Request

**Pull Request (PR):** Navigate to the original EloquentRegex repository on GitHub. You'll see an option to "Compare & pull request" for your branch. Click it to begin the process of submitting a PR.

**Describe Your Changes:** Provide a detailed description of the changes in the PR. Include any relevant issue numbers. Explaining the reasoning behind the changes and how they improve EloquentRegex will help reviewers understand your contribution.

**Submit for Review:** Once your PR is ready and all checks pass, submit it for review. At least one review from the project maintainers is required for merging. Be open to feedback and ready to make further tweaks based on suggestions.

### Review and Merge

- **Collaboration:** The project maintainers will review your PR. This process may involve some back-and-forth discussions, additional commits, and updates to your PR based on feedback.

- **Merge:** Once your PR is approved, a project maintainer will merge it into the main codebase. Congratulations, you've contributed to EloquentRegex!

### Check Out STRUCTURE.md

For more detailed information on the project structure and guidelines for contributing, please refer to the [STRUCTURE.md](https://github.com/MaestroError/eloquent-regex/blob/update-documentation-and-add-advanced-usage-section/STRUCTURE.md) file in the repository. It outlines the architecture of EloquentRegex and provides insights into naming conventions, file organization, and other best practices.

Thank you for considering contributing to EloquentRegex! Your efforts help improve the tool for developers everywhere ❤️.

# Support

Support Our Work? 🌟 You can help us keep the code flowing by making a small donation. Every bit of support goes a long way in maintaining and improving our open-source contributions. Click the button below to contribute. Thank you for your generosity!

[<img src="https://github.com/MaestroError/resources/blob/maestro/buymeamilk/green-2.png" width="300px">](https://www.buymeacoffee.com/maestroerror)

Or use QR code:

[<img src="https://github.com/MaestroError/resources/blob/maestro/buymeamilk/qr-code.png" width="135px">](https://www.buymeacoffee.com/maestroerror)

##### To Do

- Return captured groups while using `group()` method with `get()`.✔️
- Remove default quantifier inside charSet.✔️
- Remove extra "[]" inside charSet.✔️
- Rename "allowChars" option to "onlyChars".✔️
- Add options for new patterns:
  - Add `contains` and `notContains` options
  - usernameLength: Set minimum and maximum length for the username part of the email.
  - dateFormat, timeFormat: Specify the format of date and time (e.g., MM-DD-YYYY, HH:MM).
- Consider to register Patterns like options using key (name) => value (class) pairs (check performance) ✔️ (_No significant change before 50+ patterns_)
- Return collection on get method if laravel is available.
- Add builderPattern methods list MD file and link from the Docs.
- Implement usage of named groups: `/(?P<year>\d{4})-(?P<month>\d{2})-(?P<day>\d{2})/`
- Add some tool for debuging options

- Write documentation (add credit for https://regexr.com/ and ChatGPT)
  - Create quick start guide and add in Docs.
  - Add advanced usage section in Docs:
    - Options and Assertions: Detailed explanation of options, how to apply them, and their effects on patterns. ✔️
    - Filters in Extraction: Using options as filters during extraction and the types of filters available. ✔️
    - Options list ✔️
    - Ensure digits / digit behavior. ✔️
    - Regex Flags: Guide on applying regex flags to patterns for specialized matching behavior. ✔️
  - Add advanced BuilderPattern methods:
    - Grouping and Capturing: How to use groups (capturing and non-capturing) and apply quantifiers to them. ✔️
    - Sets ✔️
    - Lookaheads ✔️
    - orPattern ✔️
    - Raw methods ✔️
    - Add section in docs for "lazy" method ✔️
  - Add sections:
    - Testing and Debugging ✔️
    - Support ✔️
    - Contributing (+STRUCTURE.md) ✔️
    - Credits
    - FAQs

##### Coming next

- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Implement recursive pattern creation (Using "RI-321" string to create pattern matching this string)
- Make options controllable from config or provider (?)
- Make patterns controllable from config or provider (?)
- Implement first() method using preg_match instead of preg_match_all
- I should be able to make new pattern using BuilderPattern
- I should be able to make add custom pattern to the existing one using BuilderPattern
- Consider to add Postal Code Pattern

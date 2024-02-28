# eloquent-regex

Eloquent Regex brings the simplicity and elegance to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications.

# Overview

### Dreaming of a world where regex doesn't feel like a rocket science? 😄🚀

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
textOrNumbers(int $minLength, int $maxLength, int $minUppercase, int $minLowercase, int $minNumbers, int $maxNumbers)
```

```php
// $onlyDomains & $onlyExtensions array or string separated by comma `"example.org,example.com"`
email(int $maxLength, array|string $onlyDomains, array|string $onlyExtensions)`
```

```php
url(array|string $onlyProtocol)`
```

```php
// $onlyDomains & $onlyExtensions array or string separated by comma "org,com"
domainName(int $maxLength, array|string $onlyDomains, array|string $onlyExtensions)`
```

```php
date()
```

```php
time()
```

```php
ipAddress()
```

```php
ipv6Address()
```

```php
// $cardTypes string separated by comma "visa, amex"
creditCardNumber(string $cardTypes)
```

```php
// $countryCode should passed without "+" sign: phone("1"), phone("995")
phone(string $countryCode)
```

```php
username(int $maxLength)
```

```php
password(int $minLength, int $minUppercase, int $minNumbers, int $minSpecialChars)
```

```php
// $restrictTags & $onlyTags array or string
// separated by comma `"script, style"`.
// It isn't recomended to use both option simultaneously
htmlTag(array|string $restrictTags, array|string $onlyTags)
```

```php
// $specificCurrencies array of currency symbols or string separated by comma "$, ₾"
currency(int $minDigits, int $maxDigits, array|string $specificCurrencies)
```

```php
// $pathType allowed values: "absolute" & "relative"
filePath(int $isDirectory, bool $isFile, bool $fileExists,string $pathType) -
```

```php
filePathWin(int $isDirectory, bool $isFile, bool $fileExists)
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

To make an element optional, use '?'. This matches zero or one occurrence of the preceding element.

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
// Result: the "someText  234" would return true, the "someText 234" false
```

### One or More

To match one or more occurrences of an element, use '+', '1+', '1>' or 'oneOrMore'. This ensures the element appears at least once.

```php
// Matches strings with one or more backslashes
$result = EloquentRegex::start("\\\\")->backslash('1+')->check();
// Result: true (if one or more backslashes are found)
```

### Zero or More

The '\*' quantifier matches zero or more occurrences of the preceding element.

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

### Custom Character Sets and groups

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
and
[Groups](https://github.com/MaestroError/eloquent-regex/blob/documentation-and-examples/src/Traits/BuilderPatternTraits/GroupsTrait.php)Available
nearly all methods allowing quantifiers with values:

- Zero or More = `"zeroOrMore"`, `"0>"`, `"0+"`, `"*"`
- One or More = `"oneOrMore"`, `"1>"`, `"1+"`, `"+"`
- Optional (Zero or One) = `"optional"`, `"?"`, `"|"`
- exact amount = `2`, `"5"`
- range = `"{0,5}"`

Example: `->exact("hello world", false, "1+")`

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

##### To Do

- Add options for new patterns:
  - usernameLength: Set minimum and maximum length for the username part of the email.
  - dateFormat, timeFormat: Specify the format of date and time (e.g., MM-DD-YYYY, HH:MM).
- Consider to register Patterns like options using key (name) => value (class) pairs (check performance) ✔️ (_No significant change before 50+ patterns_)

- Write documentation (add credit for https://regexr.com/ and ChatGPT)
- Return collection on get method if laravel is available.
- Create quick start guide and add in Docs.
- Add advanced usage section in Docs:
  - Options and Assertions: Detailed explanation of options, how to apply them, and their effects on patterns.
  - Filters in Extraction: Using options as filters during extraction and the types of filters available.
  - Regex Flags: Guide on applying regex flags to patterns for specialized matching behavior.
  - Grouping and Capturing: How to use groups (capturing and non-capturing) and apply quantifiers to them.

##### Coming next

- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Implement recursive pattern creation (Using "RI-321" string to create pattern matching this string)
- Consider to add Postal Code Pattern
- Make options controllable from config or provider (?)
- Make patterns controllable from config or provider (?)
- I should be able to make new pattern using BuilderPattern
- I should be able to make add custom pattern to the existing one using BuilderPattern

```

```

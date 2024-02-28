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

````php
url(array|string $onlyProtocol)`
```php
// $onlyDomains & $onlyExtensions array or string separated by comma "org,com"
domainName(int $maxLength, array|string $onlyDomains, array|string $onlyExtensions)`
````

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
// $restrictTags & $onlyTags array or string separated by comma `"script, style"`. It isn't recomended to use both option in simultaneously
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

#### Quantifiers

Available values for quantifiers as argument:

- zeroOrMore = `"zeroOrMore"`, `"0>"`, `"0+"`
- oneOrMore = `"oneOrMore"`, `"1>"`, `"1+"`
- optional = `"optional"`, `"?"`, `"|"`

Examples: `->exact("hello world", false, "1+")`

##### To Do

- Add needed options for new patterns:
  - usernameLength: Set minimum and maximum length for the username part of the email.
  - dateFormat, timeFormat: Specify the format of date and time (e.g., MM-DD-YYYY, HH:MM).
- Consider to register Patterns like options using key (name) => value (class) pairs (check performance) ✔️ (_No significant change before 50+ patterns_)

- Extend BuilderPattern, try to add methods:

  - group(callable $callback): Creates a grouped subpattern.✔️
  - nonCapturingGroup(callable $callback): Creates a non-capturing group.✔️
  - orPattern(): Alternation, allowing for multiple possibilities.✔️
  - lookAhead(callable $callback): Positive lookahead assertion.✔️
  - lookBehind(callable $callback): Positive lookbehind assertion.✔️
  - negativeLookAhead(callable $callback): Negative lookahead assertion.✔️
  - negativeLookBehind(callable $callback): Negative lookbehind assertion.✔️
  - Raw regex methods for advanced users.✔️
  - BuilderPattern should be able to reproduce patterns used in HSA✔️

- Add benchmarks and tests for search against large data ✔️
- Add Feature Tests for BuilderPattern ✔️
- Remove need for "end" method in BuilderPattern ✔️
- Add Dockblocs and comments for new methods ✔️

- Add facade for Laravel ✔️
- Wrap Builder in class for static start ✔️
  - "string" and "source" for builder start ✔️
  - "start" and "pattern" for builderPattern start ✔️
- Write documentation (add credit for https://regexr.com/ and ChatGPT)
- Add automated tests on PR creation or on marging to main branch ✔️

- Make Tests for quantifiers (add grouping) ✔️
- Make quantifiers available for special chars ✔️
- Return collection on get method if laravel is available.
- Create quick start guide and add in Docs.

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

```

```

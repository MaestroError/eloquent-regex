# eloquent-regex

Eloquent Regex brings the simplicity and elegance to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications.

#### Adding new options

All available option classes and option names are hardcoded in `src\OptionsMapper.php`. Refer to it, if you want add new or disable existing one.

Think about options as an extra assertions you add to the pattern. To keep it simple, all options (so the option methods too) should have only 1 argument.

#### Adding new patterns

All available redy-to-use pattern classes are hardcoded in `src\Builder.php`. Refer to it, if you want add new or disable existing one.

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

- Add facade for Laravel
- Wrap Builder in class for static start
  - "string" and "source" for builder start✔️
  - "start" and "pattern" for builderPattern start✔️
- Write documentation (add credit for https://regexr.com/ and ChatGPT)
- Add automated tests on PR creation or on marging to main branch ✔️

##### Coming next

- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Implement recursive pattern creation (Using "RI-321" string to create pattern matching this string)
- Consider to add Postal Code Pattern
- Make options controllable from config or provider (?)
- Make pattern controllable from config or provider (?)
- I should be able to make new pattern using BuilderPattern
- I should be able to make add custom pattern to the existing one using BuilderPattern

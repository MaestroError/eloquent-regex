# eloquent-regex

Eloquent Regex brings the simplicity and elegance of Laravel's Eloquent ORM to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications. Streamline your pattern matching with an eloquent touch.

#### Adding new options

All available option classes and option names are hardcoded in `src\OptionsMapper.php`. Refer to it, if you want add new or disable existing one.

Think about options as an extra assertions you add to the pattern. To keep it simple, all options (so the option methods too) should have only 1 argument.

#### Quantifiers

Available values for quantifiers as argument:

- zeroOrMore = `"zeroOrMore"`, `"0>"`, `"0+"`
- oneOrMore = `"oneOrMore"`, `"1>"`, `"1+"`
- optional = `"optional"`, `"?"`, `"|"`

Examples: `->dot("zeroOrMore")` `->exact("hello worls", false, "1+")`

##### To Do

- Review all comments +
- Make patterns dynamic +
- Add commonly used patterns & tests +
- Default arguments and allow to use any other option +
- Add FilePath and DirectoryPath patterns for different OS +
- Add needed options for new patterns:
  - specialCharacters (min, max) +
  - onlyLowercase, onlyUppercase +
  - Is file (ends with .[format]) +
  - Is directory +
  - fileExists +
  - fileExtension: Specify certain file extensions (e.g., .txt, .pdf) +
  - specificCurrencies: Match only specific currency symbols.+
  - relativePath, absolutePath: Specify if the path should be relative or absolute.+
  - countryCode: Match phone numbers with specific country codes.
  - dateFormat, timeFormat: Specify the format of date and time (e.g., MM-DD-YYYY, HH:MM).
  - containSpaces: Allow or disallow spaces.
  - domainSpecific: Limit email addresses to specific domains.
  - usernameLength: Set minimum and maximum length for the username part of the email.
  - protocolSpecific: Limit URLs to specific protocols (e.g., http, https).
  - domainExtension: Restrict to certain domain extensions.
  - topLevelDomain: Specify allowed top-level domains.
  - cardType: Restrict to specific card types (e.g., Visa, MasterCard).
  - specialCharacters: Allow or disallow special characters.
  - onlyAlphanumeric: Restrict to alphanumeric characters only.
  - UsernameLength (EmailPattern): To set the minimum and maximum length for the username part of an email.
  - SpecificTags (HtmlTagPattern): To allow or restrict specific HTML tags.
  - RegexFlags: To allow users to set global regex flags (like case-insensitivity, multiline, etc.).
  - CharacterRange: To specify a range of characters that should or shouldn't appear in the pattern.
- Consider to register Patterns like options using key (name) => value (class) pairs (check performance)
- Add benchmarks and test against large data (cheap alternative of ElasticSearch)
- Extend BuilderPattern, try to add methods:

  - group(callable $callback): Creates a grouped subpattern.
  - nonCapturingGroup(callable $callback): Creates a non-capturing group.
  - orPattern(): Alternation, allowing for multiple possibilities.
  - lookAhead(callable $callback): Positive lookahead assertion.
  - lookBehind(callable $callback): Positive lookbehind assertion.
  - negativeLookAhead(callable $callback): Negative lookahead assertion.
  - negativeLookBehind(callable $callback): Negative lookbehind assertion.
  - Raw regex methods for advanced users.
  - BuilderPattern should be able to reproduce patterns used in HSA

- Add Feature Tests for BuilderPattern
- Add Dockblocs and comments for new methods

- Add facade for Laravel
- Wrap Builder in class for static start
- Write documentation (add credit for https://regexr.com/ and ChatGPT)

- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Implement recursive pattern creation (Using "RI-321" string to create pattern matching this string)

- Consider to add Postal Code Pattern
- Make options controllable from config or provider (?)
- Make pattern controllable from config or provider (?)

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
- Add commonly used patterns
- Add Dockblocs and comments for new methods
- Add facade for Laravel
- Wrap Builder in class for static start
- Write documentation
- Extend BuilderPattern, try to add methods:
  - group(callable $callback): Creates a grouped subpattern.
  - nonCapturingGroup(callable $callback): Creates a non-capturing group.
  - orPattern(): Alternation, allowing for multiple possibilities.
  - lookAhead(callable $callback): Positive lookahead assertion.
  - lookBehind(callable $callback): Positive lookbehind assertion.
  - negativeLookAhead(callable $callback): Negative lookahead assertion.
  - negativeLookBehind(callable $callback): Negative lookbehind assertion.
- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern
- Make options controllable from config or provider (?)
- Make pattern controllable from config or provider (?)

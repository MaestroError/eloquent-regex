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

- Review all comments
- Write BuilderPattern
- Implement string resolver pattern to use strings like "text(2)-digits()" (or "text:2-digits", or "text|2-digits") as pattern

3. Quantifiers

   zeroOrMore(): Matches zero or more of the preceding element.
   oneOrMore(): Matches one or more of the preceding element.
   optional(): Matches zero or one of the preceding element.

4. Special Characters and Escaping

   dot(): Matches any character except newline.
   escapeSpecialChars($string): Escapes special characters in the provided string.

5. Anchors and Boundaries

   start(): Asserts the start of a line or string.
   end(): Asserts the end of a line or string.
   wordBoundary(): Matches a word boundary.

6. Groups and Alternation

   group(callable $callback): Creates a grouped subpattern.
   nonCapturingGroup(callable $callback): Creates a non-capturing group.
   orPattern(): Alternation, allowing for multiple possibilities.

7. Lookarounds (Advanced)

   lookAhead(callable $callback): Positive lookahead assertion.
   lookBehind(callable $callback): Positive lookbehind assertion.
   negativeLookAhead(callable $callback): Negative lookahead assertion.
   negativeLookBehind(callable $callback): Negative lookbehind assertion.

Implementation Considerations

    Method Chaining: Design methods to return $this to allow chaining.
    Error Handling: Incorporate error handling, especially for methods like exact, which can create risky patterns.
    Flexibility: Ensure your methods can handle various inputs and scenarios.
    Escaping: Provide automatic escaping for special characters where appropriate.
    Documentation: Document each method clearly for ease of use.

# eloquent-regex

Eloquent Regex brings the simplicity and elegance of Laravel's Eloquent ORM to regular expressions. Designed for Laravel developers, this package offers a fluent, intuitive interface for building and executing regex patterns in your PHP applications. Streamline your pattern matching with an eloquent touch.

#### Adding new options

All available option classes and option names are hardcoded in `src\OptionsMapper.php`. Refer to it, if you want add new or disable existing one.

Think about options as an extra assertions you add to the pattern. To keep it simple, all options (so the option methods too) should have only 1 argument.

##### To Do

- Review all comments
- Write BuilderPattern

<?php

use Maestroerror\EloquentRegex\Patterns\BuilderPattern;

test('lowercaseText method generates correct regex pattern', function () {
    $builder = new BuilderPattern();
    $builder->lowercaseText(3);
    expect($builder->getPattern())->toBe('[a-z]{3}');
});

test('lowercaseText method matches correct strings', function () {
    $builder = new BuilderPattern();
    $builder->lowercaseText(3);
    $pattern = "/^" . $builder->getPattern() . "$/";
    expect(preg_match($pattern, 'abc'))->toBe(1);
    expect(preg_match($pattern, 'abcd'))->toBe(0);
    expect(preg_match($pattern, 'ABC'))->toBe(0);
});

test('textUppercase method generates correct regex pattern', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2);
    expect($builder->getPattern())->toBe('[A-Z]{2}');
});

test('textUppercase method matches correct strings', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2);
    $pattern = "/^" . $builder->getPattern() . "$/";
    expect(preg_match($pattern, 'AB'))->toBe(1);
    expect(preg_match($pattern, 'Abc'))->toBe(0);
    expect(preg_match($pattern, 'abc'))->toBe(0);
});

test('text method generates correct regex pattern', function () {
    $builder = new BuilderPattern();
    $builder->text(4);
    expect($builder->getPattern())->toBe('[a-zA-Z]{4}');
});

test('text method matches correct strings', function () {
    $builder = new BuilderPattern();
    $builder->text(4);
    $pattern = "/^" . $builder->getPattern() . "$/";
    expect(preg_match($pattern, 'aBcD'))->toBe(1);
    expect(preg_match($pattern, 'aBC1'))->toBe(0);
    expect(preg_match($pattern, 'abc'))->toBe(0);
});

test('digits method generates correct regex pattern', function () {
    $builder = new BuilderPattern();
    $builder->digits(5);
    expect($builder->getPattern())->toBe('\\d{5}');
});

test('digits method matches correct strings', function () {
    $builder = new BuilderPattern();
    $builder->digits(5);
    $pattern = "/^" . $builder->getPattern() . "$/";
    expect(preg_match($pattern, '12345'))->toBe(1);
    expect(preg_match($pattern, '1234'))->toBe(0);
    expect(preg_match($pattern, 'abcde'))->toBe(0);
});

test('combination of methods generates correct regex pattern', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2)->digits(3)->lowercaseText(1);
    expect($builder->getPattern())->toBe('[A-Z]{2}\\d{3}[a-z]{1}');
});

test('combination of methods generates correct strings', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2)->digits(3)->lowercaseText(1);
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'ER345a'))->toBe(1);
    expect(preg_match($pattern, 'MRSF'))->toBe(0);
    expect(preg_match($pattern, 'sa234a'))->toBe(0);
});

test('combination of methods generates correct regex pattern and matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2)->digits(3)->lowercaseText(1);
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'AB123c'))->toBe(1); // Correct pattern
    expect(preg_match($pattern, 'Ab123c'))->toBe(0); // Incorrect uppercase
    expect(preg_match($pattern, 'AB123'))->toBe(0);   // Missing lowercase
    expect(preg_match($pattern, 'AB12c'))->toBe(0);   // Incorrect digits
    expect(preg_match($pattern, 'AB123cd'))->toBe(0); // Extra characters
});

test('exact method generates correct regex pattern and matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->exact('Hello World');
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'Hello World'))->toBe(1); // Matches exact string
    expect(preg_match($pattern, 'hello world'))->toBe(0); // Case sensitive, does not match
    expect(preg_match($pattern, 'Hello World!'))->toBe(0); // Extra characters, does not match
    expect(preg_match($pattern, 'Hello'))->toBe(0);       // Partial string, does not match
});

test('character method generates correct regex pattern and matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->character('+');
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, '+'))->toBe(1);  // Matches the specific character
    expect(preg_match($pattern, '++'))->toBe(0); // More than one character, does not match
    expect(preg_match($pattern, 'x'))->toBe(0);  // Different character, does not match
});

test('combination of exact, character, text, and digits methods generates correct regex pattern and matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->text(2)->exact("123")->character('A', false)->digits(2);
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'ab123a12'))->toBe(1);  // Matches pattern with case-insensitive 'A'
    expect(preg_match($pattern, 'AB123A12'))->toBe(1);  // Case-insensitive match for 'A'
    expect(preg_match($pattern, 'ab123a'))->toBe(0);    // Missing digits at the end
    expect(preg_match($pattern, 'ab123B12'))->toBe(0);  // 'B' does not match 'A', even case-insensitively
});

test('exact method with case-insensitive option matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->exact('Hello', false); // False for case-insensitive
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'Hello'))->toBe(1);   // Exact case
    expect(preg_match($pattern, 'hello'))->toBe(1);   // Lowercase
    expect(preg_match($pattern, 'HeLlO'))->toBe(1);   // Mixed case
    expect(preg_match($pattern, 'HELLO'))->toBe(1);   // Uppercase
    expect(preg_match($pattern, 'HelloWorld'))->toBe(0); // Additional text
});

test('character method with case-insensitive option matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->character('X', false); // False for case-insensitive
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'X'))->toBe(1);   // Uppercase X
    expect(preg_match($pattern, 'x'))->toBe(1);   // Lowercase x
    expect(preg_match($pattern, 'Y'))->toBe(0);   // Different character
    expect(preg_match($pattern, 'XX'))->toBe(0);  // More than one character
});

test('dot method matches correctly', function () {
    $builder = new BuilderPattern();
    $builder->dot();
    
    // Test for validateInput (exact match)
    expect($builder->validateInput('.'))->toBeTrue();
    expect($builder->validateInput("\n"))->toBeFalse();
    expect($builder->validateInput("\ntest"))->toBeFalse();

    // Test for validateMatches (contains match)
    expect($builder->validateMatches('test.123'))->toBeTrue();
});

test('wordBoundary method adds a word boundary correctly', function () {
    $builder = new BuilderPattern();
    $builder->exact('test')->wordBoundary();

    expect($builder->validateInput('test'))->toBeTrue();
    expect($builder->validateMatches('test '))->toBeTrue(); // Word boundary allows space after 'test'
    expect($builder->validateMatches('testa'))->toBeFalse(); // 'testa' is not a boundary
});

test('asWord method wraps pattern with word boundaries', function () {
    $builder = new BuilderPattern();
    $builder->exact('test')->asWord();

    expect($builder->validateInput('test'))->toBeTrue();
    expect($builder->validateMatches('a test b'))->toBeTrue(); // 'test' is a whole word within the string
    expect($builder->validateMatches('atestb'))->toBeFalse(); // No word boundaries around 'test'
});

test('Combination of special character methods generates correct regex pattern and matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->textUppercase(2)->dash()->backslash()
        ->forwardSlash()->underscore()->pipe()->ampersand()->asterisk()->plus()
        ->questionMark()->atSign()->exclamationMark()->period()->comma()->semicolon()
        ->colon()->openSquareBracket()->closeSquareBracket()->openCurlyBrace()
        ->closeCurlyBrace()->openParenthesis()->closeParenthesis()->openAngleBracket()
        ->closeAngleBracket()->equalSign()->tilde()->hyphen()->minus()->doubleQuote();

    $regex = $builder->getInputValidationPattern();

    // Test the pattern against a specific string
    $testString = 'AB-\/_|&*+?@!.,;:[]{}()<>=~--"';
    expect(preg_match($regex, $testString))->toBe(1);
});


test('Special characters with textUppercase generate correct regex and match appropriately', function () {
    $builder = new BuilderPattern();
    $builder->textUppercase(2)->dash()->underscore()->ampersand();

    $expectedPattern = '/[A-Z]{2}\-_&/';
    $regex = $builder->getMatchesValidationPattern();

    expect($regex)->toBe($expectedPattern);
    expect(preg_match($regex, 'AB-_&'))->toBe(1);
    expect(preg_match($regex, 'ab-_&'))->toBe(0); // Lowercase should not match
    expect(preg_match($regex, 'AB-_'))->toBe(0); // Missing ampersand
});

test('Paired special characters generate correct regex and match appropriately', function () {
    $builder = new BuilderPattern();
    $builder->openSquareBracket()->exact('test')->closeSquareBracket();

    $expectedPattern = '/\\[test\\]/';
    $regex = $builder->getMatchesValidationPattern();

    expect($regex)->toBe($expectedPattern);
    expect(preg_match($regex, '[test]'))->toBe(1);
    expect(preg_match($regex, 'test'))->toBe(0); // Should not match without brackets
    expect(preg_match($regex, '[test'))->toBe(0); // Missing closing bracket
});

test('Escaped characters with quantifiers generate correct regex and match appropriately', function () {
    $builder = new BuilderPattern();
    $builder->doubleQuote()->exact('quoted', true, '*')->doubleQuote();

    $expectedPattern = '/"quoted*"/';
    // $expectedPattern = '/"(quoted)*"/'; // @todo Should return this
    $regex = $builder->getMatchesValidationPattern();

    expect($regex)->toBe($expectedPattern);
    expect(preg_match($regex, '"quoted"'))->toBe(1);
    // expect(preg_match($regex, '"quotedquotedquoted"'))->toBe(1); // @todo Should be true
    expect(preg_match($regex, '"quote"'))->toBe(1); // Should match as * allows for zero or more
    expect(preg_match($regex, 'quoted'))->toBe(0); // Should not match without quotes
});

test('singleQuote, tab, newLine, and carriageReturn methods generate correct regex pattern and match correctly', function () {
    $builder = new BuilderPattern();
    $builder->singleQuote()->tab()->newLine()->carriageReturn();

    $regex = $builder->getInputValidationPattern();

    // Test the pattern against a specific string
    // Using double quotes here to allow escape sequences to be interpreted
    $testString = "'\t\n\r";
    expect(preg_match($regex, $testString))->toBe(1);
});

test('lowercaseTextRange with exact \t generates correct regex pattern and match correctly', function () {
    $builder = new BuilderPattern();
    $builder->lowercaseTextRange(0, 5)->exact("\t")->digits(5);

    $regex = $builder->getInputValidationPattern();

    // Test the pattern against a specific string
    // Using double quotes here to allow escape sequences to be interpreted
    $testString = "abc\t12345";
    expect(preg_match($regex, $testString))->toBe(1);
    expect(preg_match($regex, "abc\t135"))->toBe(0);
});

test('Special character methods generate correct regex pattern and match correctly', function () {
    $builder = new BuilderPattern();
    $builder->percent()->dollar()->hash()->backtick()->caret()->unicode(0x1F600)->unicode(0x1F600); // Unicode for grinning face emoji

    $regex = $builder->getInputValidationPattern(); // Add 'u' modifier for Unicode support

    // Test the pattern against a specific string
    $testString = '%$#`^😀😀'; // The last character is the grinning face emoji
    expect(preg_match($regex, $testString))->toBe(1);
});

test('verticalTab method matches vertical tab character correctly', function () {
    $builder = new BuilderPattern();
    $builder->verticalTab();

    $regex = $builder->getInputValidationPattern();

    // Test the pattern against a string containing a vertical tab character
    expect(preg_match($regex, "\v"))->toBe(1); // Vertical tab should match
    expect(preg_match($regex, " "))->toBe(0);  // Regular space should not match
    expect(preg_match($regex, "\t"))->toBe(0); // Horizontal tab should not match
});

test('formFeed method matches form feed character correctly', function () {
    $builder = new BuilderPattern();
    $builder->formFeed();

    $regex = $builder->getInputValidationPattern();

    // Test the pattern against a string containing a form feed character
    expect(preg_match($regex, "\f"))->toBe(1); // Form feed should match
    expect(preg_match($regex, "\n"))->toBe(0); // New line should not match
    expect(preg_match($regex, "\r"))->toBe(0); // Carriage return should not match
});

test('nonWhitespace method matches non-whitespace characters', function () {
    $builder = new BuilderPattern();
    $builder->nonWhitespace(3); // Matches exactly 3 non-whitespace characters
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'ABC'))->toBe(1);
    expect(preg_match($regex, 'A B'))->toBe(0); // Contains whitespace
    expect(preg_match($regex, 'AB'))->toBe(0); // Less than 3 characters
});

test('wordChar method matches word characters', function () {
    $builder = new BuilderPattern();
    $builder->wordChar(2); // Matches exactly 2 word characters
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'Ab'))->toBe(1);
    expect(preg_match($regex, 'A1'))->toBe(1);
    expect(preg_match($regex, 'A_'))->toBe(1);
    expect(preg_match($regex, 'A '))->toBe(0); // Contains a non-word character
});

test('nonWordChar method matches non-word characters', function () {
    $builder = new BuilderPattern();
    $builder->nonWordChar(2); // Matches exactly 2 non-word characters
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '--'))->toBe(1);
    expect(preg_match($regex, '!@'))->toBe(1);
    expect(preg_match($regex, 'A@'))->toBe(0); // Contains a word character
});

test('numbers method matches numbers correctly', function () {
    $builder = new BuilderPattern();
    $builder->numbers(3); // Matches exactly 3 digits
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '123'))->toBe(1);
    expect(preg_match($regex, '12A'))->toBe(0); // Contains a non-digit
    expect(preg_match($regex, '12'))->toBe(0); // Less than 3 digits
});

test('anyNumbers method matches any number of digits correctly', function () {
    $builder = new BuilderPattern();
    $builder->anyNumbers(); // Matches any number of digits
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '12345'))->toBe(1);
    expect(preg_match($regex, 'ABC'))->toBe(0); // Contains no digits
    expect(preg_match($regex, 'ABC23'))->toBe(0); // Contains no digits
});

test('digit and nonDigits methods match single digit and non-digit characters correctly', function () {
    $builder = new BuilderPattern();
    $builder->digit(); // Matches a single digit
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '1'))->toBe(1);
    expect(preg_match($regex, 'A'))->toBe(0); // Non-digit character

    $builder = new BuilderPattern();
    $builder->nonDigits(); // Matches a single non-digit character
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'A'))->toBe(1);
    expect(preg_match($regex, '1'))->toBe(0); // Digit character
});

// Testing the range functionalities of digits and non-digits
test('numbersRange and nonDigitRange methods match ranges correctly', function () {
    $builder = new BuilderPattern();
    $builder->numbersRange(2, 4); // Matches 2 to 4 digits
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '12'))->toBe(1);
    expect(preg_match($regex, '1234'))->toBe(1);
    expect(preg_match($regex, '12345'))->toBe(0); // More than 4 digits
    expect(preg_match($regex, 'A123'))->toBe(0); // Contains a non-digit

    $builder = new BuilderPattern();
    $builder->nonDigitsRange(1, 3); // Matches 1 to 3 non-digits
    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'A'))->toBe(1);
    expect(preg_match($regex, 'ABC'))->toBe(1);
    expect(preg_match($regex, 'ABCD'))->toBe(0); // More than 3 non-digits
});

test('visibleChars method matches visible characters correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->visibleChars(3); // Matches exactly 3 visible characters
    $regex = $builder->getInputValidationPattern();

    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'a b'))->toBe(0); // Contains a space
    expect(preg_match($regex, 'ab'))->toBe(0); // Less than 3 characters
    expect(preg_match($regex, 'abcd'))->toBe(0); // More than 3 characters
});

test('invisibleChars method matches invisible characters correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->invisibleChars(2); // Matches exactly 2 invisible characters
    $regex = $builder->getInputValidationPattern();

    expect(preg_match($regex, '  '))->toBe(1);
    expect(preg_match($regex, ' a'))->toBe(0); // Contains a visible character
    expect(preg_match($regex, ' '))->toBe(0); // Only one invisible character
    expect(preg_match($regex, '   '))->toBe(0); // More than 2 invisible characters
});


test('group method creates capturing groups correctly', function () {
    $builder = new BuilderPattern();
    $builder->group(function($group) {
        $group->digits(3); // Matches exactly 3 digits
    })->character("-")->digits(2); // Followed by a hyphen and 2 digits

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, '123-45'))->toBe(1);
    expect(preg_match($regex, '12345'))->toBe(0); // No hyphen
    expect(preg_match($regex, '12-34'))->toBe(0); // First group doesn't have 3 digits
});

test('nonCapturingGroup method creates non-capturing groups correctly', function () {
    $builder = new BuilderPattern();
    $builder->nonCapturingGroup(function($group) {
        $group->digits(3); // Matches exactly 3 digits
    })->character("-")->digits(2); // Followed by a hyphen and 2 digits

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, '123-45'))->toBe(1);
    expect(preg_match($regex, '12345'))->toBe(0); // No hyphen
    expect(preg_match($regex, '12-34'))->toBe(0); // First group doesn't have 3 digits
});

test('capturing group captures content correctly', function () {
    $builder = new BuilderPattern();
    $builder->group(function($group) {
        $group->exact('abc'); 
    });

    $regex = $builder->getMatchesValidationPattern();
    $matches = [];
    preg_match($regex, 'abcdef', $matches);
    expect($matches[1])->toBe('abc'); // Check the captured content
});

test('non-capturing group does not capture content', function () {
    $builder = new BuilderPattern();
    $builder->nonCapturingGroup(function($group) {
        $group->exact('abc');
    });

    $regex = $builder->getInputValidationPattern();
    $matches = [];
    preg_match($regex, 'abcdef', $matches);
    expect(isset($matches[1]))->toBeFalse(); // No captured content
});

test('orPattern method combines patterns with alternation correctly', function () {
    $builder = new BuilderPattern();
    $builder->exact('apple')->orPattern(function($pattern) {
        $pattern->exact('orange');
    });

    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'apple'))->toBe(1);
    expect(preg_match($regex, 'orange'))->toBe(1);
    expect(preg_match($regex, 'banana'))->toBe(0); // Not part of the alternation
});

test('orPattern can be used multiple times for complex alternation', function () {
    $builder = new BuilderPattern();
    $builder->exact('apple')->orPattern(function($pattern) {
        $pattern->exact('orange');
    })->orPattern(function($pattern) {
        $pattern->exact('banana');
    });

    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, 'apple'))->toBe(1);
    expect(preg_match($regex, 'orange'))->toBe(1);
    expect(preg_match($regex, 'banana'))->toBe(1);
    expect(preg_match($regex, 'pear'))->toBe(0); // Not part of the alternation
});

test('lookAhead method adds positive lookahead correctly', function () {
    $builder = new BuilderPattern();
    $builder->digits()->lookAhead(function($pattern) {
        $pattern->character('D');
    });

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, '3D'))->toBe(1); // Digit followed by 'D'
    expect(preg_match($regex, '3A'))->toBe(0); // Digit not followed by 'D'
});

test('lookBehind method adds positive lookbehind correctly', function () {
    $builder = new BuilderPattern();
    $builder->lookBehind(function($pattern) {
        $pattern->character('P');
    })->digits();

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, 'P3'))->toBe(1); // Digit preceded by 'P', in case of get() ot will return only digit
    expect(preg_match($regex, 'A3'))->toBe(0); // Digit not preceded by 'P'
});

test('negativeLookAhead method adds negative lookahead correctly', function () {
    $builder = new BuilderPattern();
    $builder->digits()->negativeLookAhead(function($pattern) {
        $pattern->character('-');
    });

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, '3A'))->toBe(1); // Digit not followed by '-'
    expect(preg_match($regex, '3-'))->toBe(0); // Digit followed by '-'
});

test('negativeLookBehind method adds negative lookbehind correctly', function () {
    $builder = new BuilderPattern();
    $builder->negativeLookBehind(function($pattern) {
        $pattern->character('-');
    })->digits();

    $regex = $builder->getMatchesValidationPattern();
    expect(preg_match($regex, 'A3'))->toBe(1); // Digit not preceded by '-'
    expect(preg_match($regex, '-3'))->toBe(0); // Digit preceded by '-'
});

test('addRawRegex method adds raw regex patterns correctly', function () {
    $builder = new BuilderPattern();
    $builder->addRawRegex('\d{3}-\d{2}-\d{4}'); // SSN pattern

    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '123-45-6789'))->toBe(1);
    expect(preg_match($regex, '123456789'))->toBe(0);
});

test('addRawNonCapturingGroup method adds and wraps raw regex in a non-capturing group', function () {
    $builder = new BuilderPattern();
    $builder->addRawNonCapturingGroup('\d+')->exact('A');

    $regex = $builder->getInputValidationPattern();
    expect(preg_match($regex, '123A'))->toBe(1);
    expect(preg_match($regex, 'A123'))->toBe(0);
});
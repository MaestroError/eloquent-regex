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
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->exact('Hello World');
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'Hello World'))->toBe(1); // Matches exact string
    expect(preg_match($pattern, 'hello world'))->toBe(0); // Case sensitive, does not match
    expect(preg_match($pattern, 'Hello World!'))->toBe(0); // Extra characters, does not match
    expect(preg_match($pattern, 'Hello'))->toBe(0);       // Partial string, does not match
});

test('character method generates correct regex pattern and matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->character('+');
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, '+'))->toBe(1);  // Matches the specific character
    expect(preg_match($pattern, '++'))->toBe(0); // More than one character, does not match
    expect(preg_match($pattern, 'x'))->toBe(0);  // Different character, does not match
});

test('combination of exact, character, text, and digits methods generates correct regex pattern and matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->text(2)->exact("123")->character('A', false)->digits(2);
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'ab123a12'))->toBe(1);  // Matches pattern with case-insensitive 'A'
    expect(preg_match($pattern, 'AB123A12'))->toBe(1);  // Case-insensitive match for 'A'
    expect(preg_match($pattern, 'ab123a'))->toBe(0);    // Missing digits at the end
    expect(preg_match($pattern, 'ab123B12'))->toBe(0);  // 'B' does not match 'A', even case-insensitively
});

test('exact method with case-insensitive option matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->exact('Hello', false); // False for case-insensitive
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'Hello'))->toBe(1);   // Exact case
    expect(preg_match($pattern, 'hello'))->toBe(1);   // Lowercase
    expect(preg_match($pattern, 'HeLlO'))->toBe(1);   // Mixed case
    expect(preg_match($pattern, 'HELLO'))->toBe(1);   // Uppercase
    expect(preg_match($pattern, 'HelloWorld'))->toBe(0); // Additional text
});

test('character method with case-insensitive option matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->character('X', false); // False for case-insensitive
    $pattern = "/^" . $builder->getPattern() . "$/";

    expect(preg_match($pattern, 'X'))->toBe(1);   // Uppercase X
    expect(preg_match($pattern, 'x'))->toBe(1);   // Lowercase x
    expect(preg_match($pattern, 'Y'))->toBe(0);   // Different character
    expect(preg_match($pattern, 'XX'))->toBe(0);  // More than one character
});

test('dot method matches correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->dot();
    
    // Test for validateInput (exact match)
    expect($builder->validateInput('a'))->toBeTrue();
    expect($builder->validateInput(' '))->toBeTrue();
    expect($builder->validateInput("\n"))->toBeFalse(); // Dot does not match newlines
    expect($builder->validateInput("\ntest"))->toBeFalse(); // Dot does not match newlines

    // Test for validateMatches (contains match)
    expect($builder->validateMatches('test 123'))->toBeTrue();
});

test('wordBoundary method adds a word boundary correctly', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->exact('test')->wordBoundary();

    expect($builder->validateInput('test'))->toBeTrue();
    expect($builder->validateMatches('test '))->toBeTrue(); // Word boundary allows space after 'test'
    expect($builder->validateMatches('testa'))->toBeFalse(); // 'testa' is not a boundary
});

test('asWord method wraps pattern with word boundaries', function () {
    $builder = new \Maestroerror\EloquentRegex\Patterns\BuilderPattern();
    $builder->exact('test')->asWord();

    expect($builder->validateInput('test'))->toBeTrue();
    expect($builder->validateMatches('a test b'))->toBeTrue(); // 'test' is a whole word within the string
    expect($builder->validateMatches('atestb'))->toBeFalse(); // No word boundaries around 'test'
});

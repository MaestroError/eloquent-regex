<?php

use Maestroerror\EloquentRegex\Patterns\TextOrNumbersPattern;
use Maestroerror\EloquentRegex\Options\LengthOption;
use Maestroerror\EloquentRegex\Options\NumberOption;
use Maestroerror\EloquentRegex\Options\CharacterOption;

it('matches text and numbers by default', function () {
    $pattern = new TextOrNumbersPattern();
    expect($pattern->validateInput('abc123'))->toBeTrue();
    expect($pattern->validateInput('###'))->toBeFalse();
});

it('works with length option', function () {
    $pattern = new TextOrNumbersPattern();
    $lengthOption = new LengthOption();
    $lengthOption->minLength(3);

    $pattern->setOption($lengthOption);
    expect($pattern->validateInput('abcdef'))->toBeTrue();
    expect($pattern->validateInput('ab'))->toBeFalse();
});

it('validates input correctly', function () {
    $pattern = new TextOrNumbersPattern();
    $lengthOption = new LengthOption();
    $lengthOption->minLength(3);

    $pattern->setOption($lengthOption);
    expect($pattern->validateInput('abc'))->toBeTrue();
    expect($pattern->validateInput('ab'))->toBeFalse();
});

it('integrates number option correctly', function () {
    $pattern = new TextOrNumbersPattern();
    $numberOption = new NumberOption();
    $numberOption->setMinValue(2); // At least 2 digits

    $pattern->setOption($numberOption);

    expect($pattern->validateInput('abc123'))->toBeTrue(); // Has at least 2 digits
    expect($pattern->validateInput('abc'))->toBeFalse(); // Less than 2 digits
});

it('integrates character option correctly', function () {
    $pattern = new TextOrNumbersPattern();
    $charOption = new CharacterOption();
    $charOption->allow(['a', 'b', 'c', '1', '2']);
    $charOption->minLowercase(2); // At least 2 lowercase letters

    $pattern->setOption($charOption);

    expect($pattern->validateInput('ab12'))->toBeTrue(); // Meets lowercase and allowed character conditions
    expect($pattern->validateInput('xyz12'))->toBeFalse(); // 'x', 'y', 'z' not allowed
    expect($pattern->validateInput('a1'))->toBeFalse(); // Only 1 lowercase letter
});

it('find text and numbers matches', function () {
    $pattern = new TextOrNumbersPattern();
    $matches = $pattern->getMatches('<> abc123 ### %%%%% <>');
    expect(count($matches))->toBe(1);
    expect($matches[0])->toBe('abc123');
});

it('validates (min 1) match in full string correctly', function () {
    $pattern = new TextOrNumbersPattern();
    expect($pattern->validateMatches('<> abc123 ### %%%%% <>'))->toBe(true);
    expect($pattern->validateMatches('<> ### %%%%% <>'))->toBe(false);
});
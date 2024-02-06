<?php

use Maestroerror\EloquentRegex\Options\LengthOption;

it('enforces minimum length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->minLength(3);
    expect($lengthOption->validate('abc'))->toBeTrue();
    expect($lengthOption->validate('ab'))->toBeFalse();
});

it('enforces maximum length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->maxLength(3);
    expect($lengthOption->validate('abc'))->toBeTrue();
    expect($lengthOption->validate('abcd'))->toBeFalse();
});

it('enforces exact length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->exactLength(3);
    expect($lengthOption->validate('abc'))->toBeTrue();
    expect($lengthOption->validate('ab'))->toBeFalse();
    expect($lengthOption->validate('abcd'))->toBeFalse();
});

it('allows optional length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->optional();
    expect($lengthOption->validate(''))->toBeTrue(); // empty string is valid when optional
});

it('generates correct regex pattern for length constraints', function () {
    $lengthOption = new LengthOption();
    $lengthOption->minLength(2)->maxLength(4);
    expect($lengthOption->build())->toBe('{2,4}');

    $lengthOption->reset()->exactLength(3);
    expect($lengthOption->build())->toBe('{3}');

    $lengthOption->reset()->optional();
    expect($lengthOption->build())->toBe('(?:.+)?'); // Optional any character, one or more times
});


it('matches strings according to minimum length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->minLength(3);
    $regex = "/^." . $lengthOption->build() . "$/";
    echo $regex;
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'ab'))->toBe(0);
});

it('matches strings according to maximum length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->maxLength(3);
    $regex = "/^." . $lengthOption->build() . "$/";
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'abcd'))->toBe(0);
});

it('matches strings according to exact length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->exactLength(3);
    $regex = "/^." . $lengthOption->build() . "$/";
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'ab'))->toBe(0);
    expect(preg_match($regex, 'abcd'))->toBe(0);
});

it('matches strings according to optional length', function () {
    $lengthOption = new LengthOption();
    $lengthOption->optional();
    // $lengthOption->maxLength(3);
    $regex = "/^" . $lengthOption->build() . "$/";
    expect(preg_match($regex, ''))->toBe(1); // Empty string should match when optional
    expect(preg_match($regex, 'abc'))->toBe(1); // Non-empty string should also match
});

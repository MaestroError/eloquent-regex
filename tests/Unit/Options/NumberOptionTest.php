<?php

use Maestroerror\EloquentRegex\Options\NumberOption;

it('matches any number of digits by default', function () {
    $numberOption = new NumberOption();
    expect($numberOption->validate('12345'))->toBeTrue();
});

it('enforces minimum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMinValue(2);
    expect($numberOption->validate('123'))->toBeTrue();
    expect($numberOption->validate('1'))->toBeFalse(); // Only 1 digit, minimum is 2
});

it('enforces maximum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMaxValue(3);
    expect($numberOption->validate('123'))->toBeTrue();
    expect($numberOption->validate('1234'))->toBeFalse(); // 4 digits, maximum is 3
});

it('enforces exact number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setExactValue(3);
    expect($numberOption->validate('123'))->toBeTrue();
    expect($numberOption->validate('1234'))->toBeFalse(); // 4 digits, exact is 3
});

it('allows optional numbers', function () {
    $numberOption = new NumberOption();
    $numberOption->optional();
    expect($numberOption->validate(''))->toBeTrue(); // No digits is valid when optional
});

it('correctly handles number ranges', function () {
    $numberOption = new NumberOption();
    $numberOption->range(2, 4);
    expect($numberOption->validate('12'))->toBeTrue();
    expect($numberOption->validate('1234'))->toBeTrue();
    expect($numberOption->validate('1'))->toBeFalse(); // Less than min
    expect($numberOption->validate('12345'))->toBeFalse(); // More than max
});

it('generates correct regex pattern for default behavior', function () {
    $numberOption = new NumberOption();
    expect($numberOption->build())->toBe('\d+');
});

it('generates correct regex pattern for minimum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMinValue(2);
    expect($numberOption->build())->toBe('\d{2,}');
});

it('generates correct regex pattern for maximum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMaxValue(3);
    expect($numberOption->build())->toBe('\d{0,3}');
});

it('generates correct regex pattern for exact number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setExactValue(3);
    expect($numberOption->build())->toBe('\d{3}');
});

it('generates correct regex pattern for optional numbers', function () {
    $numberOption = new NumberOption();
    $numberOption->optional();
    expect($numberOption->build())->toBe('(?:\d+)?');
});

it('generates correct regex pattern for number ranges', function () {
    $numberOption = new NumberOption();
    $numberOption->range(2, 4);
    expect($numberOption->build())->toBe('\d{2,4}');
});

it('matches numbers according to the default regex pattern', function () {
    $numberOption = new NumberOption();
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, '12345'))->toBe(1);
    expect(preg_match($regex, 'abc'))->toBe(0);
});

it('matches numbers according to the minimum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMinValue(2);
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, '12'))->toBe(1);
    expect(preg_match($regex, '1'))->toBe(0);
});

it('matches numbers according to the maximum number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setMaxValue(3);
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, '123'))->toBe(1);
    expect(preg_match($regex, '1234'))->toBe(0);
});

it('matches numbers according to the exact number of digits', function () {
    $numberOption = new NumberOption();
    $numberOption->setExactValue(3);
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, '123'))->toBe(1);
    expect(preg_match($regex, '1234'))->toBe(0);
});

it('matches numbers according to the optional setting', function () {
    $numberOption = new NumberOption();
    $numberOption->optional();
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, ''))->toBe(1);
    expect(preg_match($regex, '123'))->toBe(1);
});

it('matches numbers according to the specified range', function () {
    $numberOption = new NumberOption();
    $numberOption->range(2, 4);
    $regex = "/^" . $numberOption->build() . "$/";
    expect(preg_match($regex, '12'))->toBe(1);
    expect(preg_match($regex, '1234'))->toBe(1);
    expect(preg_match($regex, '1'))->toBe(0);
    expect(preg_match($regex, '12345'))->toBe(0);
});

<?php

use Maestroerror\EloquentRegex\Options\CharacterOption;

it('enforces allowed characters', function () {
    $charOption = new CharacterOption();
    $charOption->allow(['a', 'b', 'c']);
    expect($charOption->validate('abc'))->toBeTrue();
    expect($charOption->validate('abd'))->toBeFalse(); // 'd' is not allowed
});

it('excludes specific characters', function () {
    $charOption = new CharacterOption();
    $charOption->exclude(['x', 'y', 'z']);
    expect($charOption->validate('abc'))->toBeTrue();
    expect($charOption->validate('abx'))->toBeFalse(); // 'x' is excluded
});

it('enforces minimum uppercase characters', function () {
    $charOption = new CharacterOption();
    $charOption->minUppercase(2);
    expect($charOption->validate('ABc'))->toBeTrue();
    expect($charOption->validate('Abc'))->toBeFalse(); // Only 1 uppercase
});

it('enforces minimum lowercase characters', function () {
    $charOption = new CharacterOption();
    $charOption->minLowercase(2);
    expect($charOption->validate('abC'))->toBeTrue();
    expect($charOption->validate('aBC'))->toBeFalse(); // Only 1 lowercase
});


it('generates correct regex pattern for allowed characters', function () {
    $charOption = new CharacterOption();
    $charOption->allow(['a', 'b', 'c']);
    expect($charOption->build())->toBe('[abc]+');
});

it('generates correct regex pattern for excluded characters', function () {
    $charOption = new CharacterOption();
    $charOption->exclude(['x', 'y', 'z']);
    expect($charOption->build())->toBe('(?!.*[xyz]).*');
});

it('generates regex with optional flag', function () {
    $charOption = new CharacterOption();
    $charOption->optional();
    expect($charOption->build())->toBe('(?:.*)?'); // Any character, optional
});

it('matches strings with allowed characters only', function () {
    $charOption = new CharacterOption();
    $charOption->allow(['a', 'b', 'c']);
    $regex = "/^" . $charOption->build() . "$/";
    // echo $regex;
    expect(preg_match($regex, 'aa'))->toBe(1);
    expect(preg_match($regex, 'd'))->toBe(0);
});

it('rejects strings with excluded characters', function () {
    $charOption = new CharacterOption();
    $charOption->exclude(['x', 'y', 'z']);
    $regex = "/^" . $charOption->build() . "$/";
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'axy'))->toBe(0); // Contains excluded character 'x'
});

it('requires minimum number of uppercase characters', function () {
    $charOption = new CharacterOption();
    $charOption->minUppercase(2);
    $regex = "/" . $charOption->build() . "/";
    expect(preg_match($regex, 'ABc'))->toBe(1); // 2 uppercase characters
    expect(preg_match($regex, 'Abc'))->toBe(0); // Only 1 uppercase, less than the minimum required
});


it('requires minimum number of lowercase characters', function () {
    $charOption = new CharacterOption();
    $charOption->minLowercase(2);
    $regex = "/^" . $charOption->build() . "$/";
    expect(preg_match($regex, 'abC'))->toBe(1);
    expect(preg_match($regex, 'aBC'))->toBe(0); // Only 1 lowercase, less than the minimum required
});


it('requires minimum number of lowercase characters and excludes some', function () {
    $charOption = new CharacterOption();
    $charOption->minLowercase(2);
    $charOption->exclude(['x', 'y', 'z']);
    $regex = "/^" . $charOption->build() . "$/";
    expect(preg_match($regex, 'abC'))->toBe(1);
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'abz'))->toBe(0);
    expect(preg_match($regex, 'aBC'))->toBe(0);
});


it('requires minimum number of lowercase, allows some characters and excludes some', function () {
    $charOption = new CharacterOption();
    $charOption->allow(['a', 'b', 'c', "Z"]);
    $charOption->exclude(['x', 'y', 'z']);
    $charOption->minLowercase(2);
    $regex = "/^" . $charOption->build() . "$/";
    echo $regex;
    expect(preg_match($regex, 'abc'))->toBe(1);
    expect(preg_match($regex, 'aZ'))->toBe(0);
    expect(preg_match($regex, 'aaaaaaZ'))->toBe(1);
    expect(preg_match($regex, 'hgf'))->toBe(0);
    expect(preg_match($regex, 'xyz'))->toBe(0); 
});

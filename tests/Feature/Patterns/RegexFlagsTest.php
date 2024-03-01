<?php

use Maestroerror\EloquentRegex\Builder;

it('validates emails case insensitively', function () {
    $builder = new Builder("Example@Email.COM");
    $checkWithoutFlag = $builder->start()
        ->lowercaseText()
        ->character("@")
        ->lowercaseText()
        ->dot()
        ->uppercaseText()
        ->end()
        ->check();

    expect($checkWithoutFlag)->toBeFalse();

    $builder = new Builder("Example@Email.COM");
    $checkWithFlag = $builder->start()
        ->lowercaseText()
        ->character("@")
        ->lowercaseText()
        ->dot()
        ->uppercaseText()
        ->end()
        ->asCaseInsensitive()->check();

    expect($checkWithFlag)->toBeTrue();
});

it('matches dates across multiple lines', function () {
    $string = "2024-01-30\n 2024-02-15\n 2024-11-30";
    $builder = new Builder($string);
    $matches = $builder->start()
        ->digits(4)->dash()
        ->digits(2)->dash()
        ->digits(2)
        ->end()
        ->asMultiline()->check();
    expect($matches)->toBeTrue();
});

it('matches a text in multiline as a single line string', function () {
    $string = "Check out\n this site:";
    $builder = new Builder($string);
    $check = $builder->start()->anyChars()->character(":")->end()->asSingleline()->check();
    expect($check)->toBeTrue(); // It don't match without Singleline flag
});

it('matches text with Unicode characters', function () {
    $string = "მზადაა #1 ✔️ და #2 ✔️";
    $builder = new Builder($string);
    $matches = $builder->start()->wordCharsRange(0, 2)->end()->asUnicode()->get();
    expect($matches)->toContain('და'); // It don't match without unicode char
});
<?php

use Maestroerror\EloquentRegex\Options\OnlyAlphanumericOption;

it('validates alphanumeric strings correctly', function () {
    $option = new OnlyAlphanumericOption();
    $option->onlyAlphanumeric(true);

    // Alphanumeric strings
    expect($option->validate('abc123'))->toBeTrue();
    expect($option->validate('TestUser2021'))->toBeTrue();

    // Non-alphanumeric strings
    expect($option->validate('abc123!'))->toBeFalse();
    expect($option->validate('hello world'))->toBeFalse();
});

it('allows non-alphanumeric strings when option is disabled', function () {
    $option = new OnlyAlphanumericOption();
    $option->onlyAlphanumeric(false);

    expect($option->validate('abc123'))->toBeTrue();
    expect($option->validate('abc123!'))->toBeTrue();
    expect($option->validate('hello world'))->toBeTrue();
});

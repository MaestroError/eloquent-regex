<?php

use Maestroerror\EloquentRegex\Builder;

it('matches different phone number formats correctly', function () {
    $string = "Contact us at +1 (555) 123-4567, 1234567890, or 0044 20 7946 0958.";
    $builder = new Builder($string);

    $matches = $builder->phone()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['+1 (555) 123-4567', '1234567890', '0044 20 7946 0958']);
});

it('validates a single phone number format correctly', function () {
    $string = "+1-800-123-4567";
    $builder = new Builder($string);

    $check = $builder->phone()->check();

    // Assert that the single phone number format is validated correctly
    expect($check)->toBeTrue();
});

it('validates a phone number format correctly within a string', function () {
    $string = "Call us now at 800-123-4567 for more information.";
    $builder = new Builder($string);

    $check = $builder->phone()->checkString();

    // Assert that the phone number format within a string is validated correctly
    expect($check)->toBeTrue();
});

it('does not match invalid phone numbers', function () {
    $string = "Invalid numbers: 123, +1 24, 567_890";
    $builder = new Builder($string);

    $matches = $builder->phone()->get();

    // Assert that no valid phone numbers are matched
    expect($matches)->toBeEmpty();
});



it('validates a single phone number using country code correctly', function () {
    $string = "+1-800-123-4567";
    $builder = new Builder($string);

    $check = $builder->phone("1")->check();

    // Assert that the single phone number format is validated correctly
    expect($check)->toBeTrue();
});
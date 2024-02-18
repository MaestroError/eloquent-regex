<?php

use Maestroerror\EloquentRegex\Builder;

it('matches different date formats correctly', function () {
    $string = "Important dates are 2024-01-30, 2024/01/30, 30.01.2024, and 30.01.24.";
    $builder = new Builder($string);

    $matches = $builder->date()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['2024-01-30', '2024/01/30', '30.01.2024', '30.01.24']);
});

it('validates a single date format correctly', function () {
    $string = "Deadline: 2024-04-20";
    $builder = new Builder($string);

    $check = $builder->date()->check();

    // Assert that the single date format is validated correctly
    expect($check)->toBeTrue();
});


it('validates a single date format is incorrect', function () {
    $string = "Deadline: 04-2024-20";
    $builder = new Builder($string);

    $check = $builder->date()->check();

    // Assert that the single date format is validated correctly
    expect($check)->toBeFalse();
});
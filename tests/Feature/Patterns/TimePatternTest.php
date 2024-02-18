<?php

use Maestroerror\EloquentRegex\Builder;

it('matches different time formats correctly', function () {
    $string = "Meeting times are 08:30, 14:45:15, and 12:00 PM.";
    $builder = new Builder($string);

    $matches = $builder->time()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['08:30', '14:45:15', '12:00 PM']);
});

it('validates a single time format correctly', function () {
    $string = "07:15 AM";
    $builder = new Builder($string);

    $check = $builder->time()->check();

    // Assert that the single time format is validated correctly
    expect($check)->toBeTrue();
});


it('validates a single time format correctly in string', function () {
    $string = "Alarm set for 07:15 AM";
    $builder = new Builder($string);

    $check = $builder->time()->checkString();

    // Assert that the single time format is validated correctly
    expect($check)->toBeTrue();
});
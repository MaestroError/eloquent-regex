<?php

use Maestroerror\EloquentRegex\Builder;

it('matches various currency formats correctly', function () {
    $builder = new Builder("$1,000.00, €200, £30.5, ￥1,250");

    $matches = $builder->currency()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['$1,000.00', '€200', '£30.5', '￥1,250']);
});

it('does not match invalid currency formats', function () {
    $builder = new Builder("1000, 200.999, 30,50$");

    $check = $builder->currency()->checkString();

    // Assert that invalid currency formats are not matched
    expect($check)->toBeFalse();
});
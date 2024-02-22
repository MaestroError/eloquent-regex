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

it('matches currencies with a minimum number of digits', function () {
    $builder = new Builder("$100, €20, £3");

    $matches = $builder->currency(2)->get(); // minDigits

    // Assert that only currency amounts with at least 2 digits are matched
    expect($matches)->toEqual(['$100', '€20']);
});

it('matches currencies with a maximum number of digits', function () {
    $builder = new Builder("$1000, €200, £30");

    $matches = $builder->currency(0, 3)->get(); // maxDigits

    // Assert that only currency amounts with no more than 3 digits are matched
    expect($matches)->toEqual(['€200', '£30']);
});

it('matches specific currency symbols', function () {
    $builder = new Builder("$1000, €200, £30, ￥1250");

    $matches = $builder->currency(0, 0, "$,€")->get();

    // Assert that only specified currency symbols are matched
    expect($matches)->toEqual(['$1000', '€200']);
});

it('does not match currencies outside specific symbols', function () {
    $builder = new Builder("$1000, €200, £30");

    $check = $builder->currency(function($query) {
        return $query->specificCurrencies(['￥']);
    })->checkString();

    // Assert that currencies not in the specific symbols are not matched
    expect($check)->toBeFalse();
});
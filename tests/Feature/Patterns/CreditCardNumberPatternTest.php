<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid credit card numbers correctly', function () {
    $string = "Visa: 4111 1111 1111 1111, MasterCard: 5500 0000 0000 0004, Amex: 3400 000000 00009

        Visa: 4111-1111-1111-1111, MasterCard: 5500-0000-0000-0004, Amex: 3400-000000-00009
        
        Visa: 4111111111111111, MasterCard: 5500000000000004, Amex: 340000000000009";
    $builder = new Builder($string);
    $matches = $builder->creditCardNumber()->get();

    expect($matches)->toEqual([
        '4111 1111 1111 1111',
        '5500 0000 0000 0004',
        '3400 000000 00009',
        '4111-1111-1111-1111',
        '5500-0000-0000-0004',
        '3400-000000-00009',
        '4111111111111111',
        '5500000000000004',
        '340000000000009',
    ]);
});

it('does not match invalid credit card numbers', function () {
    $string = "Invalid: 1234 5678 9012 3456, Another: 0000 0000 0000 0000, Another 2400 000000 00009";
    $builder = new Builder($string);
    $matches = $builder->creditCardNumber()->get();

    expect($matches)->toBeEmpty();
});


it('checks valid credit card number', function () {
    $string = "4111 1111 1111 1111";
    $builder = new Builder($string);
    $check = $builder->creditCardNumber()->check();

    expect($check)->toBeTrue();
});


it('checks invalid credit card number correctly', function () {
    $string = "1111 1111 1111 1111";
    $builder = new Builder($string);
    $check = $builder->creditCardNumber()->check();

    expect($check)->toBeFalse();
});
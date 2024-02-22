<?php

use Maestroerror\EloquentRegex\Options\SpecificCurrenciesOption;

test('validates input with specific currency symbols', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->setSpecificCurrencies(['$', '€', '£']);

    expect($currencyOption->validate('$100'))->toBeTrue();
    expect($currencyOption->validate('€200'))->toBeTrue();
    expect($currencyOption->validate('£300'))->toBeTrue();
    expect($currencyOption->validate('₾400'))->toBeFalse(); // Not in the specified currencies
});

test('validates input with a single specific currency symbol', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->onlyUSD();

    expect($currencyOption->validate('$100'))->toBeTrue();
    expect($currencyOption->validate('€200'))->toBeFalse();
});

test('allows setting specific currencies as a string', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->setSpecificCurrencies('$,€,£');

    expect($currencyOption->validate('$100'))->toBeTrue();
    expect($currencyOption->validate('€200'))->toBeTrue();
    expect($currencyOption->validate('₾400'))->toBeFalse();
});

test('validates using onlyEUR option', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->onlyEUR();

    expect($currencyOption->validate('€200'))->toBeTrue();
    expect($currencyOption->validate('$100'))->toBeFalse();
});

test('validates using onlyGBP option', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->onlyGBP();

    expect($currencyOption->validate('£300'))->toBeTrue();
    expect($currencyOption->validate('₾400'))->toBeFalse();
});

test('validates using onlyGEL option', function () {
    $currencyOption = new SpecificCurrenciesOption();
    $currencyOption->onlyGEL();

    expect($currencyOption->validate('₾400'))->toBeTrue();
    expect($currencyOption->validate('$100'))->toBeFalse();
});

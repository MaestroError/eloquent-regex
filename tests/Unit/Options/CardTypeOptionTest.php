<?php

use Maestroerror\EloquentRegex\Options\CardTypeOption;

it('validates Visa card numbers correctly', function () {
    $cardTypeOption = new CardTypeOption();
    $cardTypeOption->onlyVisa();

    expect($cardTypeOption->validate('4111111111111111'))->toBeTrue(); // Valid (Visa)
    expect($cardTypeOption->validate('5500000000000004'))->toBeFalse(); // Invalid (MasterCard)
});

it('validates MasterCard numbers correctly', function () {
    $cardTypeOption = new CardTypeOption();
    $cardTypeOption->onlyMasterCard();

    expect($cardTypeOption->validate('5500000000000004'))->toBeTrue(); // Valid (MasterCard)
    expect($cardTypeOption->validate('4111111111111111'))->toBeFalse(); // Invalid (Visa)
});

it('validates American Express card numbers correctly', function () {
    $cardTypeOption = new CardTypeOption();
    $cardTypeOption->onlyAmex();

    expect($cardTypeOption->validate('371449635398431'))->toBeTrue(); // Valid (AMEX)
    expect($cardTypeOption->validate('4111111111111111'))->toBeFalse(); // Invalid (Visa)
    expect($cardTypeOption->validate('5500000000000004'))->toBeFalse(); // Invalid (MasterCard)
});

it('validates multiple card types correctly', function () {
    $cardTypeOption = new CardTypeOption();
    $cardTypeOption->allowCardTypes('visa,mastercard,amex');

    expect($cardTypeOption->validate('4111111111111111'))->toBeTrue(); // Valid Visa
    expect($cardTypeOption->validate('5500000000000004'))->toBeTrue(); // Valid MasterCard
    expect($cardTypeOption->validate('371449635398431'))->toBeTrue(); // Valid AMEX
    expect($cardTypeOption->validate('6011111111111117'))->toBeFalse(); // Invalid Discover
});

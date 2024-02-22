<?php

use Maestroerror\EloquentRegex\Options\CountryCodeOption;

it('validates phone numbers with specific country codes', function () {
    $countryCodeOption = new CountryCodeOption();
    $countryCodeOption->setCountryCode('995');

    expect($countryCodeOption->validate('+995123456789'))->toBeTrue();
    expect($countryCodeOption->validate('995123456789'))->toBeTrue();
    expect($countryCodeOption->validate('123456789'))->toBeFalse(); // Missing country code
    expect($countryCodeOption->validate('+123123456789'))->toBeFalse(); // Different country code
});

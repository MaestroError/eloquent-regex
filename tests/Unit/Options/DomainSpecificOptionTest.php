<?php

use Maestroerror\EloquentRegex\Options\DomainSpecificOption;

it('validates email addresses based on specific domains', function () {
    $domainOption = new DomainSpecificOption();
    $domainOption->setAllowedDomains(['example.com', 'example.org']);

    // Test allowed domains
    expect($domainOption->validate('user@example.com'))->toBeTrue();
    expect($domainOption->validate('user@example.org'))->toBeTrue();

    // Test disallowed domain
    expect($domainOption->validate('user@anotherdomain.com'))->toBeFalse();
});

it('validates email addresses based on specific domain extensions', function () {
    $domainOption = new DomainSpecificOption();
    $domainOption->setAllowedExtensions(['com', 'org']);

    // Test allowed extensions
    expect($domainOption->validate('user@example.com'))->toBeTrue();
    expect($domainOption->validate('user@example.org'))->toBeTrue();

    // Test disallowed extension
    expect($domainOption->validate('user@example.net'))->toBeFalse();
});
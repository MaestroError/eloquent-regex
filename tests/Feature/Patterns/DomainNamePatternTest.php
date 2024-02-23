<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid domain names correctly', function () {
    $string = "Visit our site at example.com or connect to sub.domain.org for more info.";
    $builder = new Builder($string);

    $matches = $builder->domainName()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['example.com', 'sub.domain.org']);
});

it('validates a single domain name correctly', function () {
    $string = "Check our portal: portal.example.net";
    $builder = new Builder($string);

    $check = $builder->domainName()->checkString(); // Check if the string contains at least one domain name

    // Assert that the string contains a valid domain name
    expect($check)->toBeTrue();
});

it('counts domain names correctly', function () {
    $string = "Multiple domains: first.com, second.org, third.co.uk";
    $builder = new Builder($string);

    $count = $builder->domainName()->count(); // Count the number of domain names

    // Assert that the count matches the expected number of domain names
    expect($count)->toEqual(3);
});

it('matches domain names with specific extensions', function () {
    $string = "example.com, example.org, example.net";
    $builder = new Builder($string);

    $matches = $builder->domainName(0, "", 'com,org')->get();

    // Assert that only domains with specified extensions are matched
    expect($matches)->toEqual(['example.com', 'example.org']);
});

it('matches domain names from specific domains', function () {
    $string = "visit example.com, contact us at info@example.org";
    $builder = new Builder($string);

    $matches = $builder->domainName(['onlyDomains' => ['example.com']])->get();

    // Assert that only domains from the specified list are matched
    expect($matches)->toEqual(['example.com']);
});
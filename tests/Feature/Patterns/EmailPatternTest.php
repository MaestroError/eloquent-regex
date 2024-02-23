<?php

use Maestroerror\EloquentRegex\Builder;

it('correctly validates a valid email with EmailPattern', function () {
    $builder = new Builder("example@email.com");
    $check = $builder->email()->check();
    expect($check)->toBeTrue();
});

it('correctly invalidates an incorrect email with EmailPattern', function () {
    $builder = new Builder("example@.com");
    $check = $builder->email()->check();
    expect($check)->toBeFalse();
});

it('correctly validates a valid hard email with EmailPattern', function () {
    $builder = new Builder("example-hard@email.com.ge");
    $check = $builder->email()->check();
    expect($check)->toBeTrue();
});

it('correctly finds a valid email with EmailPattern', function () {
    $text = "Please contact us at support@example.com for general inquiries. \n
        For technical support, reach out to tech.help@exampletech.com. Additionally, \n
        you can send your feedback directly to feedback@example.net. We look forward to hearing from you!";
    $builder = new Builder($text);
    $get = $builder->email()->get();
    expect(count($get))->toBe(3);
});

it('correctly invalidates an incorrect email with EmailPattern and MaxLength option', function () {
    $builder = new Builder("example@email.com");
    $check = $builder->email(10)->check();
    expect($check)->toBeFalse();
});

it('validates email addresses with specific domain extensions', function () {
    $builder = new Builder("user@example.com");

    $check = $builder->email(['onlyExtensions' => ['com', 'org']])->check();

    // Assert that the email with the specified extension is validated correctly
    expect($check)->toBeTrue();
});

it('does not validate email addresses with unlisted domain extensions', function () {
    $builder = new Builder("user@example.net");

    $check = $builder->email(function($query) {
        return $query->onlyExtensions(['com', 'org']);
    })->check();

    // Assert that the email with an unlisted extension is not validated
    expect($check)->toBeFalse();
});

it('validates email addresses from specific domains', function () {
    $builder = new Builder("user@example.com");

    $check = $builder->email(['onlyDomains' => ['example.com', 'example.org']])->check();

    // Assert that the email from the specified domain is validated correctly
    expect($check)->toBeTrue();
});

it('does not validate email addresses from unlisted domains', function () {
    $builder = new Builder("user@otherdomain.com");

    $check = $builder->email(['onlyDomains' => ['example.com', 'example.org']])->check();

    // Assert that the email from an unlisted domain is not validated
    expect($check)->toBeFalse();
});
<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid URLs correctly', function () {
    $string = "Visit our site at https://www.example.com or follow us on http://social.example.org";
    $builder = new Builder($string);

    $matches = $builder->url()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['https://www.example.com', 'http://social.example.org']);
});

it('validates a single URL correctly', function () {
    $string = "https://www.example.com";
    $builder = new Builder($string);

    $check = $builder->url()->check();

    // Assert that the URL validation passes
    expect($check)->toBeTrue();
});

it('matches valid URLs with URI correctly', function () {
    $string = "Visit our site at https://www.example.com/home or follow us on http://social.example.org/profile/name";
    $builder = new Builder($string);

    $matches = $builder->url()->get();

    expect($matches)->toEqual(['https://www.example.com/home', 'http://social.example.org/profile/name']);
});

it('validates URLs with specific protocols', function () {
    $builder = new Builder("http://example.com");

    $check = $builder->url('http')->check();

    // Assert that the URL with the specified protocol is validated correctly
    expect($check)->toBeTrue();
});

it('does not validate URLs with unlisted protocols', function () {
    $builder = new Builder("ftp://example.com");

    $check = $builder->url('https')->check();

    // Assert that the URL with an unlisted protocol is not validated
    expect($check)->toBeFalse();
});

it('validates URLs with only HTTP protocol', function () {
    $builder = new Builder("http://example.com");

    $check = $builder->url(['onlyHttp' => true])->check();

    // Assert that the URL with only HTTP protocol is validated correctly
    expect($check)->toBeTrue();
});

it('validates URLs with only HTTPS protocol', function () {
    $builder = new Builder("https://example.com");

    $check = $builder->url(['onlyHttps' => true])->check();

    // Assert that the URL with only HTTPS protocol is validated correctly
    expect($check)->toBeTrue();
});

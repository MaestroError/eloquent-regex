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

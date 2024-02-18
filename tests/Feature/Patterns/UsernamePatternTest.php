<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid usernames correctly', function () {
    $string = "user1, _user_name, user-name123";
    $builder = new Builder($string);

    $matches = $builder->username()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['user1', '_user_name', 'user-name123']);
});

it('validates a single username format correctly', function () {
    $string = "user2023";
    $builder = new Builder($string);

    $check = $builder->username()->check();

    // Assert that the single username format is validated correctly
    expect($check)->toBeTrue();
});

it('validates a username format correctly within a string', function () {
    $string = "My username is user_2023.";
    $builder = new Builder($string);

    $check = $builder->username()->checkString();

    // Assert that the username format within a string is validated correctly
    expect($check)->toBeTrue();
});

it('does not match invalid usernames', function () {
    $string = "us, user@name.com, verylongusernamebeyondlimit";
    $builder = new Builder($string);

    $matches = $builder->username()->get();

    // Assert that no valid usernames are matched
    expect($matches)->toBeEmpty();
});

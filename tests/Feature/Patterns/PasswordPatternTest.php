<?php

use Maestroerror\EloquentRegex\Builder;

it('validates strong passwords correctly', function () {
    $builder = new Builder("StrongP@ssw0rd");

    $check = $builder->password(8, 1, 1)->check();

    // Assert that the password is strong
    expect($check)->toBeTrue();
});

it('rejects weak passwords correctly', function () {
    $builder = new Builder("weak");

    $check = $builder->password(8, 1, 1)->check();

    // Assert that the password is weak
    expect($check)->toBeFalse();
});
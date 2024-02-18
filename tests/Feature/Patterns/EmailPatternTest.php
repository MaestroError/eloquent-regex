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

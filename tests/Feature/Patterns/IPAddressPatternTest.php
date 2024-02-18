<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid IPv4 addresses correctly', function () {
    $string = "192.168.1.1, 10.0.0.1, 255.255.255.255";
    $builder = new Builder($string);
    $matches = $builder->ipAddress()->get();
    expect($matches)->toEqual(['192.168.1.1', '10.0.0.1', '255.255.255.255']);
});

it('does not match invalid IPv4 addresses', function () {
    $string = "192.168.1.256, 10.0.0.999, 300.255.255.255";
    $builder = new Builder($string);
    $matches = $builder->ipAddress()->get();
    expect($matches)->toBeEmpty();
});

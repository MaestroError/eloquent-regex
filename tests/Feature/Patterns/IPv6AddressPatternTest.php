<?php

use Maestroerror\EloquentRegex\Builder;

it('matches valid IPv6 addresses correctly', function () {
    $string = "2001:0db8:85a3:0000:0000:8a2e:0370:7334, ::1";
    $builder = new Builder($string);
    $matches = $builder->ipv6Address()->get();

    expect($matches)->toEqual(['2001:0db8:85a3:0000:0000:8a2e:0370:7334']);
});

it('does not match invalid IPv6 addresses', function () {
    $string = "2001:0db8:85a3:0000:0000:8a2e:0370:7334Z, 2001::85a3::7334";
    $builder = new Builder($string);
    $matches = $builder->ipv6Address()->get();

    expect($matches)->toBeEmpty();
});

it('validates IPv6 addresses using filter_var', function () {
    $validIPv6 = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
    $invalidIPv6 = "2001:0db8:85a3:0000:0000:8a2e:0370:7334Z";

    $builder = new Builder($validIPv6);
    expect($builder->ipv6Address()->check())->toBeTrue();

    $builder->setString($invalidIPv6);
    expect($builder->ipv6Address()->check())->toBeFalse();
});

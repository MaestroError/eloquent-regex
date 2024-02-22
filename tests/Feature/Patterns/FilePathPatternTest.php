<?php

use Maestroerror\EloquentRegex\Builder;


it('matches valid Linux file paths correctly', function () {
    $string = "/home/user/document.pdf, /var/log/syslog, /etc/nginx/nginx.conf";
    $builder = new Builder($string);

    $matches = $builder->filePath()->get();

    expect($matches)->toEqual(['/home/user/document.pdf', '/var/log/syslog', '/etc/nginx/nginx.conf']);
});

it('validates a single Linux file path correctly', function () {
    $string = "/usr/local/bin/script.sh";
    $builder = new Builder($string);

    $check = $builder->filePath()->check();

    expect($check)->toBeTrue();
});

it('does not match invalid Linux file paths', function () {
    $string = "This is not a file path: /invalid//path";
    $builder = new Builder($string);

    $matches = $builder->filePath()->get();

    expect($matches)->toBeEmpty();
});

it('does not validate a Linux file path in a string without a valid path', function () {
    $string = "This string does not contain a valid file path.";
    $builder = new Builder($string);

    $check = $builder->filePath()->checkString();

    expect($check)->toBeFalse();
});

it('get path using isFile option', function () {
    $string = "/usr/local/bin/script.sh /home/user/document.pdf";
    $builder = new Builder($string);

    $check = $builder->filePath(0, "sh")->get();

    expect($check)->toEqual(["/usr/local/bin/script.sh"]);
});

it('checks path using isFile option', function () {
    $string = "/usr/local/bin/script.sh";
    $builder = new Builder($string);

    $check = $builder->filePath(0, null)->check();

    expect($check)->toBeTrue();
});

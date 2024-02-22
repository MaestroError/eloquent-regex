<?php

use Maestroerror\EloquentRegex\Options\PathTypeOption;

it('validates relative paths', function () {
    $pathOption = new PathTypeOption();
    $pathOption->setPathType(1);

    expect($pathOption->validate('file.txt'))->toBeTrue();
    expect($pathOption->validate('/home/user/file.txt'))->toBeFalse(); // Absolute path
});

it('validates absolute paths', function () {
    $pathOption = new PathTypeOption();
    $pathOption->setPathType(2);

    expect($pathOption->validate('/home/user/file.txt'))->toBeTrue();
    expect($pathOption->validate('file.txt'))->toBeFalse(); // Relative path
});

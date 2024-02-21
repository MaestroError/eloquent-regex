<?php

use Maestroerror\EloquentRegex\Options\FileOption;
use Maestroerror\EloquentRegex\Options\FileExistsOption;

it('validates if the input is a file with a specific extension', function () {
    $fileOption = new FileOption();
    $fileOption->isFile('txt');
    expect($fileOption->validate('document.txt'))->toBeTrue();
    expect($fileOption->validate('image.jpg'))->toBeFalse(); // Wrong extension
});

it('validates if the input is a file regardless of extension', function () {
    $fileOption = new FileOption();
    $fileOption->isFile();
    expect($fileOption->validate('document.txt'))->toBeTrue();
    expect($fileOption->validate('folder/'))->toBeFalse(); // Not a file
});

it('validates if the input is a directory', function () {
    $fileOption = new FileOption();
    $fileOption->isDirectory();
    expect($fileOption->validate('folder/'))->toBeTrue();
    expect($fileOption->validate('document.txt'))->toBeFalse(); // Not a directory
});

it('validates if the file exists in the filesystem', function () {
    $fileOption = new FileExistsOption();
    $fileOption->fileExists();
    expect($fileOption->validate(__DIR__.'/../../TestFiles/document.txt'))->toBeTrue();
    expect($fileOption->validate('path/to/nonexistent/file.txt'))->toBeFalse();
});

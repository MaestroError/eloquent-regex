<?php

use Maestroerror\EloquentRegex\Builder;

it('matches Windows file paths correctly', function () {
    $string = "C:\\Users\\Example\\file.txt, D:\\Documents\\report.docx, E:\\Photos\\image.jpg";
    $builder = new Builder($string);

    $matches = $builder->filePathWin()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual([
        'C:\\Users\\Example\\file.txt',
        'D:\\Documents\\report.docx',
        'E:\\Photos\\image.jpg'
    ]);
});

it('validates a single Windows file path correctly', function () {
    $string = "C:\\Program Files\\app\\config.ini";
    $builder = new Builder($string);

    $check = $builder->filePathWin()->check();

    // Assert that the single file path is validated correctly
    expect($check)->toBeTrue();
});

it('validates a single Windows file path correctly in string', function () {
    $string = "Please refer to C:\\Program Files\\app\\config.ini for configuration settings.";
    $builder = new Builder($string);

    $check = $builder->filePathWin()->checkString();

    // Assert that the single file path is validated correctly in the string
    expect($check)->toBeTrue();
});

it('does not match invalid Windows file paths', function () {
    $string = "InvalidPath\\file.txt, Another\\Invalid\\Path";
    $builder = new Builder($string);

    $matches = $builder->filePathWin()->get();

    // Assert that no invalid file paths are matched
    expect($matches)->toBeEmpty();
});

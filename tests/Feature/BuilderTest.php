<?php

use Maestroerror\EloquentRegex\Builder;

it('reproduces alt prefix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()->exact("alt=")->group(function ($pattern) {
        $pattern->doubleQuote()->orPattern(function ($pattern) {
            $pattern->singleQuote();
        });
    })->end()->toRegex();

    
    expect($regex)->toBe("alt\=(\"|')");
});

it('reproduces hashtag prefix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()->lookBehind(function ($pattern) {
        $pattern->set(function ($pattern) {
            $pattern->doubleQuote()->closeAngleBracket()->addRawRegex("\\s");
        });
    })->hash()->end()->toRegex(); 

    expect($regex)->toBe('(?<=["\>\s])\#');
});

it('reproduces Text suffix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()
        ->openAngleBracket()->slash()->alphanumericRange(0, 10)->closeAngleBracket()
        ->end()->toRegex();

    expect($regex)->toBe('\<\/[a-zA-Z0-9]{0,10}\>');
});
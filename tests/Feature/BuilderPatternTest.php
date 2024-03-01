<?php

use Maestroerror\EloquentRegex\Builder;

it('reproduces alt prefix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()->exact("alt=")->group(function ($pattern) {
        $pattern->doubleQuote()->orPattern(function ($pattern) {
            $pattern->singleQuote();
        });
    })->toRegex();

    
    expect($regex)->toBe("alt\=(\"|')");
});

it('reproduces hashtag prefix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()->lookBehind(function ($pattern) {
        $pattern->charSet(function ($pattern) {
            $pattern->doubleQuote()->closeAngleBracket()->addRawRegex("\\s");
        });
    })->hash()->toRegex(); 

    expect($regex)->toBe('(?<=["\>\s])\#');
});

it('reproduces Text suffix pattern from HSA', function () {
    $builder = new Builder("");

    $regex = $builder->start()
        ->openAngleBracket()->slash()->alphanumericRange(0, 10)->closeAngleBracket()
        ->toRegex();

    expect($regex)->toBe('\<\/[a-zA-Z0-9]{0,10}\>');
});

it('constructs regex for simple email validation', function () {
    $builder = new Builder();

    $regex = $builder->start()
            ->textLowercase()
            ->atSymbol()
            ->textLowercase()
            ->dot()
            ->textLowercaseRange(2, 4)
            ->toRegex();

    expect($regex)->toBe('[a-z]+@[a-z]+\.[a-z]{2,4}');
});

it('constructs regex for URL validation', function () {
    $builder = new Builder();

    $regex = $builder->start()
            ->exact(['http', 'https'])
            ->colon()
            ->doubleSlash()
            ->text()
            ->dot()
            ->text()
            ->toRegex();

    expect($regex)->toBe('(http|https)\:\/\/[a-zA-Z]+\.[a-zA-Z]+');
});

it('constructs regex for specific phone number format', function () {
    $builder = new Builder();

    $regex = $builder->start()
            ->openParenthesis()
            ->digits(3)
            ->closeParenthesis()
            ->space()
            ->digits(3)
            ->dash()
            ->digits(4)
            ->toRegex();

    expect($regex)->toBe('\(\d{3}\) \d{3}\-\d{4}');
});

it('extracts dates in specific format from text', function () {
    $builder = new Builder("Meeting on 2021-09-15 and 2021-10-20");

    $matches = $builder->start()
                       ->digits(4)
                       ->dash()
                       ->digits(2)
                       ->dash()
                       ->digits(2)
                       ->get();

    expect($matches)->toEqual(['2021-09-15', '2021-10-20']);
});

it('validates usernames in a string', function () {
    $builder = new Builder("Users: user_123, JohnDoe99");

    $check = $builder->start()
                     ->alphanumeric()
                     ->underscore()
                     ->digitsRange(0, 2)
                     ->checkString();

    expect($check)->toBeTrue();
});

it('extracts hashtags from text', function () {
    $builder = new Builder("#hello #world This is a #test");

    $matches = $builder->start()
                       ->hash()
                       ->text()
                       ->get();

    expect($matches)->toEqual(['#hello', '#world', '#test']);
});

it('extracts secret coded messages from text', function () {
    $text = "Normal text {secret: message one} more text {secret: another hidden text} end";
    $builder = new Builder($text);

    // Pattern: Look for curly braces containing 'secret: ' followed by any characters (non-greedy)
    $matches = $builder->start()
        ->lookBehind(function ($pattern) {
            $pattern->openCurlyBrace()->exact('secret: ');
        })
        ->lazy()->anyChars()
        ->lookAhead(function ($pattern) {
            $pattern->closeCurlyBrace();
        })
        ->get();

    // Expected secret messages are 'secret: message one' and 'secret: another hidden text'
    expect($matches)->toEqual(['message one', 'another hidden text']);
});


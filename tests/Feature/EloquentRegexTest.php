<?php

use Maestroerror\EloquentRegex\EloquentRegex;

// Custom Pattern tests:

it('reproduces alt prefix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->exact("alt=")
        ->group(function ($pattern) {
            $pattern->doubleQuote()->orPattern(function ($pattern) {
                $pattern->singleQuote();
            });
        })->toRegex();

    expect($regex)->toBe("alt\=(\"|')");
});

it('reproduces hashtag prefix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->lookBehind(function ($pattern) {
            $pattern->charSet(function ($pattern) {
                $pattern->doubleQuote()->closeAngleBracket()->addRawRegex("\\s");
            });
        })->hash()->toRegex(); 

    expect($regex)->toBe('(?<=["\>\s])\#');
});

it('reproduces Text suffix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->openAngleBracket()->slash()->alphanumericRange(0, 10)->closeAngleBracket()
        ->toRegex();

    expect($regex)->toBe('\<\/[a-zA-Z0-9]{0,10}\>');
});

it('constructs regex for simple email validation using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->textLowercase()
        ->atSymbol()
        ->textLowercase()
        ->dot()
        ->textLowercaseRange(2, 4)
        ->toRegex();

    expect($regex)->toBe('[a-z]+@[a-z]+\.[a-z]{2,4}');
});

it('constructs regex for URL validation using wrapper', function () {
    $regex = EloquentRegex::builder()->pattern()
        ->exact(['http', 'https'])
        ->colon()
        ->doubleSlash()
        ->text()
        ->dot()
        ->text()
        ->toRegex();

    expect($regex)->toBe('(http|https)\:\/\/[a-zA-Z]+\.[a-zA-Z]+');
});

it('constructs regex for specific phone number format using wrapper', function () {
    $regex = EloquentRegex::builder()->pattern(function ($p) {
            $p->openParenthesis()->digits(3)->closeParenthesis()
            ->space()
            ->digits(3)->dash()->digits(4);
        })->toRegex();

    expect($regex)->toBe('\(\d{3}\) \d{3}\-\d{4}');
});

it('extracts dates in specific format from text using wrapper', function () {
    $matches = EloquentRegex::customPattern("Meeting on 2021-09-15 and 2021-10-20")
        ->digits(4)
        ->dash()
        ->digits(2)
        ->dash()
        ->digits(2)
        ->get();

    expect($matches)->toEqual(['2021-09-15', '2021-10-20']);
});

it('validates usernames in a string using wrapper and LengthOption', function () {
    $check = EloquentRegex::customPattern("Users: user_123, JohnDoe99")
        ->alphanumeric()
        ->underscore("?")
        ->digitsRange(0, 2)
        ->end()->setOptions(["maxLength" => 15])
        ->checkString();

    expect($check)->toBeTrue();
});

it('extracts hashtags from text using wrapper', function () {
    $matches = EloquentRegex::start("#hello #world This is a #test")
        ->hash()
        ->text()
        ->get();

    expect($matches)->toEqual(['#hello', '#world', '#test']);
});

it('extracts secret coded messages from text using wrapper', function () {
    $text = "Normal text {secret: message one} more text {secret: another hidden text} end";
    $matches = EloquentRegex::start($text)
        ->lookBehind(function ($pattern) {
            $pattern->openCurlyBrace()->exact('secret: ');
        })
        ->lazy()->anyChar()
        ->lookAhead(function ($pattern) {
            $pattern->closeCurlyBrace();
        })
        ->get();

    expect($matches)->toEqual(['message one', 'another hidden text']);
});

// Ready-to-use pattern tests:

// TextOrNumbersPattern
it('validates text with numbers correctly', function () {
    $builder = EloquentRegex::source("Text123");

    $check = $builder->textOrNumbers()->check();

    expect($check)->toBeTrue();
});

// EmailPattern
it('validates an email address correctly', function () {
    $builder = EloquentRegex::source("test@example.com");

    $check = $builder->email()->check();

    expect($check)->toBeTrue();
});

// DomainNamePattern
it('validates a domain name correctly', function () {
    $builder = EloquentRegex::string("example.com");

    $check = $builder->domainName()->check();

    expect($check)->toBeTrue();
});

// DatePattern
it('validates a date format correctly', function () {
    $builder = EloquentRegex::string("2023-01-01");

    $check = $builder->date('Y-m-d')->check();

    expect($check)->toBeTrue();
});

// TimePattern
it('validates a time format correctly', function () {
    $builder = EloquentRegex::string("23:59");

    $check = $builder->time('H:i')->check();

    expect($check)->toBeTrue();
});

// IPAddressPattern
it('validates an IPv4 address correctly', function () {
    $builder = EloquentRegex::string("192.168.1.1");

    $check = $builder->ipAddress()->check();

    expect($check)->toBeTrue();
});

// IPv6AddressPattern
it('validates an IPv6 address correctly', function () {
    $builder = EloquentRegex::string("2001:0db8:85a3:0000:0000:8a2e:0370:7334");

    $check = $builder->ipv6Address()->check();

    expect($check)->toBeTrue();
});

// CreditCardNumberPattern
it('validates a credit card number correctly', function () {
    $builder = EloquentRegex::source("4111111111111111"); // A common Visa test number

    $check = $builder->creditCardNumber()->check();

    expect($check)->toBeTrue();
});

// PhonePattern
it('validates a phone number correctly', function () {
    $builder = EloquentRegex::string("+1 (123) 456-7890");

    $check = $builder->phone()->check();

    expect($check)->toBeTrue();
});

// UsernamePattern
it('validates a username correctly', function () {
    $builder = EloquentRegex::string("user_123");

    $check = $builder->username()->check();

    expect($check)->toBeTrue();
});

// HtmlTagPattern
it('identifies HTML content correctly', function () {
    $builder = EloquentRegex::source("<div>example</div>");

    $check = $builder->htmlTag()->check();

    expect($check)->toBeTrue();
});

// CurrencyPattern
it('validates currency format correctly', function () {
    $builder = EloquentRegex::string("$100.00");

    $check = $builder->currency()->check();

    expect($check)->toBeTrue();
});

// FilePathPattern
it('validates a Unix file path correctly', function () {
    $string = "/user/directory/file.txt";
    $builder = EloquentRegex::string($string);

    $check = $builder->filePath([
        "isDirectory" => false,
        "isFile" => "txt",
    ])->check();

    expect($check)->toBeTrue();
});

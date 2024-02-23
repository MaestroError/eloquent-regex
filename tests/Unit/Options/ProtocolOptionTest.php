<?php

use Maestroerror\EloquentRegex\Options\ProtocolOption;

it('validates URLs with allowed protocols', function () {
    $protocolOption = new ProtocolOption();
    $protocolOption->onlyProtocol('http');

    expect($protocolOption->validate('http://example.com'))->toBeTrue();
    expect($protocolOption->validate('https://example.com'))->toBeFalse();
    expect($protocolOption->validate('ftp://example.com'))->toBeFalse(); // 'ftp' protocol not allowed
});

it('validates URLs with only HTTP protocol', function () {
    $protocolOption = new ProtocolOption();
    $protocolOption->onlyHttp(true);

    expect($protocolOption->validate('http://example.com'))->toBeTrue();
    expect($protocolOption->validate('https://example.com'))->toBeFalse(); // Only 'http' allowed
    expect($protocolOption->validate('ftp://example.com'))->toBeFalse();
});

it('validates URLs with only HTTPS protocol', function () {
    $protocolOption = new ProtocolOption();
    $protocolOption->onlyHttps(true);

    expect($protocolOption->validate('https://example.com'))->toBeTrue();
    expect($protocolOption->validate('http://example.com'))->toBeFalse(); // Only 'https' allowed
    expect($protocolOption->validate('ftp://example.com'))->toBeFalse();
});

it('allows multiple protocols', function () {
    $protocolOption = new ProtocolOption();
    $protocolOption->onlyProtocol(['http', 'ftp']);

    expect($protocolOption->validate('http://example.com'))->toBeTrue();
    expect($protocolOption->validate('ftp://example.com'))->toBeTrue();
    expect($protocolOption->validate('https://example.com'))->toBeFalse(); // 'https' not in the allowed list
});

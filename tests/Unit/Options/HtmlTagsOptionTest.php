<?php

use Maestroerror\EloquentRegex\Options\HtmlTagsOption;

it('allows specified HTML tags', function () {
    $option = new HtmlTagsOption();
    $option->allowTags(['p', 'b']);

    expect($option->validate('<p class="description">Paragraph</p>'))->toBeTrue();
    expect($option->validate('<p><b>Bold</b>text</p>'))->toBeTrue();
    expect($option->validate('<div>Div</div>'))->toBeFalse(); // 'div' is not in the allowed list
});

it('restricts specified HTML tags', function () {
    $option = new HtmlTagsOption();
    $option->restrictTags(['script', 'iframe']);

    expect($option->validate('<p>Paragraph</p>'))->toBeTrue();
    expect($option->validate('<script>alert("Hello")</script>'))->toBeFalse(); // 'script' is restricted
    expect($option->validate('<iframe src="example.com"></iframe>'))->toBeFalse(); // 'iframe' is restricted
});

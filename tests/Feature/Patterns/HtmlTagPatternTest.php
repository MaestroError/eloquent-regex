<?php

use Maestroerror\EloquentRegex\Builder;

it('matches HTML tags correctly', function () {
    $builder = new Builder("<p>Paragr<small>aph</small></p> <div>Div content</div>");

    $matches = $builder->htmlTag()->get();

    // Assert that the returned matches are as expected
    expect($matches)->toEqual(['<p>Paragr<small>aph</small></p>', '<div>Div content</div>']);
});

it('does not match invalid HTML tags', function () {
    $builder = new Builder("This is <not> a valid </tag>");

    $check = $builder->htmlTag()->checkString();

    // Assert that invalid HTML tags are not matched
    expect($check)->toBeFalse();
});

<?php

use Maestroerror\EloquentRegex\Builder;

it('matches exact string with TextOrNumbersPattern', function () {
    $input = "Revaz1621";
    $builder = new Builder($input);
    $check = $builder->textOrNumbers(8, 0, 1)->check();
    expect($check)->toBeTrue();
});

it('finds pattern within string using TextOrNumbersPattern', function () {
    $string = "asd Revaz1621 wawd";
    $builder = new Builder($string);
    $check = $builder->textOrNumbers([
        "minLength" => 8,
        "minUppercase" => 1
    ])->checkString();
    expect($check)->toBeTrue();
});

it('counts matches correctly with TextOrNumbersPattern', function () {
    $string = "asd Revaz1621 Wawoline343 text here";
    $builder = new Builder($string);
    $count = $builder->textOrNumbers(function($query) {
        return $query->minLength(8)->minUppercase(1);
    })->count();
    expect($count)->toEqual(2);
});

it('retrieves all matches using TextOrNumbersPattern', function () {
    $string = "Revaz1621 an 1sada a 5464565";
    $builder = (new Builder($string))->textOrNumbers(4);
    $get = $builder->get();
    expect($get)->toBeArray()->toHaveLength(3);
});

it('generates correct regex string with TextOrNumbersPattern', function () {
    $builder = (new Builder("Revaz1621"))->textOrNumbers(4);
    $regex = $builder->toRegex();
    expect($regex)->toEqual("[a-zA-Z0-9]"); // @todo Regex isn't usable, need to update toRegex method
    // expect($regex)->toEqual("/^[a-zA-Z0-9]$/");
});

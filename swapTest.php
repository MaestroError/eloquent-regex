<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\EloquentRegex;


$builder = EloquentRegex::start("RI-2142, RI-1234, KI-8996, PO-2555");
$result = $builder->namedGroup(function ($pattern) {
        return $pattern->textUppercase(2);
    }, "project", 1)
    ->dash()
    ->namedGroup(function ($pattern) {
        return $pattern->digitsRange(2, 4);
    }, "issue", 1)->end();

$results = $result->swap(function ($data) {
    return "In project '" . $data["project"] . "' issue #" . $data["issue"] . " is in progress";
});

print_r($results);


$builder = EloquentRegex::start("/container-tbilisi-1585");
$result = $builder->slash()
    ->exact("container")
    ->dash()
    ->namedGroup(function ($pattern) {
        return $pattern->text();
    }, "CITY", 1)
    ->dash()
    ->namedGroup(function ($pattern) {
        return $pattern->digitsRange(2, 5);
    }, "id", 1)->end();

    $results = $result->swap("/container/[ID]?city=[CITY]");
    
    print_r($results);
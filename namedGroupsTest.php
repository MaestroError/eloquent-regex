<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;


$builder = new Builder("RI-2142, RI-1234, KI-8996, PO-2555");
$result = $builder->pattern(function ($builder) {
    return $builder
    ->namedGroup(function ($pattern) {
        return $pattern->textUppercase(2);
    }, "project", 1)
    ->dash()
    ->namedGroup(function ($pattern) {
        return $pattern->digitsRange(2, 4);
    }, "issue", 1);
});

$results = $result->get();

foreach ($results as $item) {
    $id = $item["result"];
    $projectPart = $item["groups"]["project"];
    $issueNumber = $item["groups"]["issue"];
    echo "ID: $id; Project: $projectPart; Issue: $issueNumber\n";
}
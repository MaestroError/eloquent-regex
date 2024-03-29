<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;

$input = "Revaz1621";
$builder = new Builder($input);
// Min 8 chars, min 1 uppercase
$check = $builder->textOrNumbers(8, 0, 1)->check(); // Exact same (entire input from start ^ to end $)
print_r($check);

echo "\n";

$string = "asd Revaz1621 wawd";
$builder = new Builder($string);
// Min 8 chars, min 1 uppercase
$check = $builder->textOrNumbers([
    "minLength" => 8,
    "minUppercase" => 1
])->checkString(); // Includes min 1
print_r($check);

echo "\n";

$string = "asd Revaz1621 Wawoline343 text here";
$builder = new Builder($string);
$count = $builder->textOrNumbers(function($query) {
    return $query->minLength(8)->minUppercase(1);
})->count(); // Returns number of matches
print_r("Count: " . $count);

echo "\n";


$string = "Revaz1621 an 1sada a 5464565";
$builder = (new Builder($string))->textOrNumbers(4);
$get = $builder->get();
print_r($get);

echo "\n";

$regex = $builder->toRegex();
print_r($regex);
echo "\n";

// $string = "Revaz1621 an 1sada a 5464565";
// $builder = (new Builder($string))->textOrNumbers("string")->check();

$SR = new Builder("RI-214");
$check = $SR->start()->textUppercase(2)->dash()->digits()->end()->check();
print_r($check); // true

echo "\n";

$SR = new Builder("RI-2142");
$check = $SR->pattern(function ($builder) {
    return $builder->textUppercase(2)->dash()->digits(3);
})->check();
print_r($check); // false

echo "\n";



$SR = new Builder("RI-214 - A nice task");
$get = $SR->pattern(function ($builder) {
    return $builder->textUppercase(2)->dash()->digits(3);
})->get();
print_r($get); //

echo "\n";
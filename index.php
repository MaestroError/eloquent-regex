<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;

$input = "Passs1234";
$builder = new Builder($input);
$check = $builder->textOrNumbers(8)->check(); // Exact same
print_r($check);

echo "\n";

$string = "asd Passs1234 wawd";
$builder = new Builder($string);
$check = $builder->textOrNumbers(8)->checkString(); // Includes min 1
print_r($check);

echo "\n";

$string = "asd Passs1234 Wawoline343 text here";
$builder = new Builder($string);
$count = $builder->textOrNumbers(8)->count(); // Returns number of matches
print_r($count);

echo "\n";


$string = "Passs1234 an 1sada a 5464565";
$builder = (new Builder($string))->textOrNumbers(4);
$get = $builder->get();
print_r($get);

echo "\n";

// Deprecated for now
// $regex = $builder->toRegex();
// print_r($regex);
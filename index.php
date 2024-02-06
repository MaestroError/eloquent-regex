<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;

$string = "Passs1234";
$builder = new Builder($string);
$check = $builder->textOrNumbers(8)->check();
print_r($check);

echo "\n";


$string = "Passs1234 an 1sada a 5464565";
$builder = (new Builder($string))->textOrNumbers(4);
$get = $builder->get();
print_r($get);

echo "\n";

$regex = $builder->toRegex();
print_r($regex);
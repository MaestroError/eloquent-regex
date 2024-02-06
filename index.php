<?php

require __DIR__ . "/vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;

$builder = new Builder();
$pattern = $builder->textAndNumbers(8)->get();
echo $pattern;
echo "\n";
echo preg_match($pattern, "Revaz1621");
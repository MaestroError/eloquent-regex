<?php

require __DIR__ . "/../vendor/autoload.php";

use Maestroerror\EloquentRegex\Builder;

ini_set('memory_limit', '1024M');

// Test IDs: 65d9f50d647f2b833e9734aa, 65d9f50aa8f5ea8f53c9ab9f
// Interesting fact: As shorter keyword is as faster works the search 
$testFiles = [
    // 1 match (kw: "65d9f50aa8f5ea8f53c9ab9f"), multiple matches (kw: "green"), multiple matches decoded, decoded as full string
    "data.1000.test", // 8 ms, 7.6 ms (890), 44.8 ms (890), 11.5 ms
    "data.2500.test", // 20ms, 17.25 ms (2186), 108 ms (2186), 112 ms
    "data.5000.test", // 40.9ms, 34.4 ms (4347), 219 ms (4347), 223 ms
    "data.10k.test", // 79.7ms, 67.4 ms (8669), 413 ms (8669), 447 ms
    "data.20k.test", // 159ms, 131 ms (17313), 823 ms (17313), 890 ms
];

/*
===========================================================
| ROWS | Found in (count)  | F & Decoded | F & D as whole |
===========================================================
| 1000 | 7.6 ms (890)      | 44.8 ms     | 11.5 ms        |
| 2500 | 17.25ms (2186)    | 108 ms      | 30 ms          |
| 5000 | 34.4 ms (4347)    | 219 ms      | 62 ms          |
| 10K  | 67.4 ms (8669)    | 413 ms      | 112 ms         |
| 20K  | 131 ms (17313)    | 823 ms      | 251 ms         |
===========================================================
===Keyword:="green"========================================
*/

function search($keyword, $file) {
    $data = file_get_contents(__DIR__."/".$file);
    preg_match_all("/.+(?=$keyword).+/", $data, $matches);
    return $matches[0];
}

function makeObject($json) {
    return json_decode($json);
}

function decode($data) {
    $json = implode(",", $data);
    // file_put_contents("testing.json", "[" . $json . "]");
    return json_decode("[" . $json . "]");
}

function test($keyword, $file, $print = false) {
    $start = microtime(true);
    $data = search($keyword, $file);
    $time_elapsed_secs = microtime(true) - $start;
    echo "Search of $file took: " . $time_elapsed_secs . "\n";
    echo "Matches found: " . count($data) . "\n";
    if ($print) {
        // $data = array_map("makeObject", $data);
        $data = decode($data);
        // var_dump($data);
        $time_elapsed_secs = microtime(true) - $start;
        echo "Search and decode of $file took: " . $time_elapsed_secs . "\n";
    }
    return $data;
}



// test('"eyeColor":"green"', "data.1000.test", true);
// test("green", "data.20k.test", true);
// test("Livingston Hendricks", "data.5000.test", true);

test("65d9f50aa8f5ea8f53c9ab9f", "data.1000.test", true);

$start = microtime(true);
// Using decode function:
// 1k 10 times is faster (112 ms) then 10K at once (465 ms)
// test("green", "data.10k.test", true);
for ($i=0; $i < 10; $i++) { 
    test("green", "data.1000.test", true);
}
$time_elapsed_secs = microtime(true) - $start;
echo "Search and decode took: " . $time_elapsed_secs . "\n";
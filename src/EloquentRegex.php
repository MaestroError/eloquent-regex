<?php

namespace Maestroerror\EloquentRegex;

use Maestroerror\EloquentRegex\Builder;


class EloquentRegex {

    public static function string(string $str) {
        return (new Builder)->source($str);
    }

    public static function source(string $str) {
        return (new Builder)->source($str);
    }

    public static function start(string $str) {
        return (new Builder)->source($str)->start();
    }

    public static function customPattern(string $str) {
        return (new Builder)->source($str)->start();
    }

    public static function builder() {
        return new Builder;
    }
}
<?php

namespace Maestroerror\EloquentRegex\Traits;

/* @deprecated */
trait IsOptionalTrait {
    protected $isOptional = false;

    public function optional() {
        $this->isOptional = true;
        return $this;
    }

    public function isOptional(): bool {
        return $this->isOptional;
    }
}

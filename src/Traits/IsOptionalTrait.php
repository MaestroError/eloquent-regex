<?php

namespace Maestroerror\EloquentRegex\Traits;

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

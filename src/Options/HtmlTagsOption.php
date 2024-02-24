<?php

namespace Maestroerror\EloquentRegex\Options;

use Maestroerror\EloquentRegex\Contracts\OptionContract;

class HtmlTagsOption implements OptionContract {

    private array $allowedTags = [];
    private array $restrictedTags = [];

    public function validate(string $input): bool {
        // Check if any restricted tags are present
        foreach ($this->restrictedTags as $tag) {
            $tag = trim($tag);
            if (strpos($input, "<$tag") !== false) {
                return false;
            }
        }

        // If allowed tags are specified, check if all tags in input are allowed
        if (!empty($this->allowedTags)) {
            preg_match_all('/<([a-z]+)[\s>]/i', $input, $matches);
            foreach ($matches[1] as $tag) {
                if (!in_array(strtolower($tag), $this->allowedTags)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function build(): string {
        // This method is not used for HTML tag validation
        return "";
    }

    public function allowTags(array|string $tags): self {
        if (!is_array($tags)) {
            $tags = explode(",", $tags);
        }
        // Trim spaces
        $tags = array_map('trim', $tags);
        // Make lower
        $this->allowedTags = array_map('strtolower', $tags);
        return $this;
    }

    public function restrictTags(array|string $tags): self {
        if (!is_array($tags)) {
            $tags = explode(",", $tags);
        }
        // Trim spaces
        $tags = array_map('trim', $tags);
        // Make lower
        $this->restrictedTags = array_map('strtolower', $tags);
        return $this;
    }
}

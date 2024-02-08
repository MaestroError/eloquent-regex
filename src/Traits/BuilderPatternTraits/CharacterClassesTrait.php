<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait CharacterClassesTrait {

    // Character Classes START

    public function lowercaseText($length = null): self {
        $this->handleTextLowercase($length);
        return $this;
    }

    public function textLowercase($length = null): self {
        $this->handleTextLowercase($length);
        return $this;
    }

    public function textLowercaseRange($minLength = 0, $maxLength = 0): self {
        $this->handleTextLowercase(null, $minLength, $maxLength);
        return $this;
    }

    public function lowercaseTextRange($minLength = 0, $maxLength = 0): self {
        $this->handleTextLowercase(null, $minLength, $maxLength);
        return $this;
    }

    private function handleTextLowercase($length = null, $minLength = 0, $maxLength = 0): void {
        $this->pattern .= "[a-z]";
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function textUppercase($length = null): self {
        $this->handleTextUppercase($length);
        return $this;
    }
    
    public function uppercaseText($length = null): self {
        $this->handleTextUppercase($length);
        return $this;
    }
    
    public function textUppercaseRange($minLength = 0, $maxLength = 0): self {
        $this->handleTextUppercase(null, $minLength, $maxLength);
        return $this;
    }
    
    public function uppercaseTextRange($minLength = 0, $maxLength = 0): self {
        $this->handleTextUppercase(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleTextUppercase($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "[A-Z]";
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function text($length = null): self {
        $this->handleText($length);
        return $this;
    }
    
    public function textRange($minLength = 0, $maxLength = 0): self {
        $this->handleText(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleText($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "[a-zA-Z]";  // Matches both uppercase and lowercase letters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function digits($length = null): self {
        $this->handleDigits($length);
        return $this;
    }
    
    public function digitsRange($minLength = 0, $maxLength = 0): self {
        $this->handleDigits(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleDigits($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\d";  // Matches digits
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }
    
    public function alphanumeric($length = null): self {
        $this->handleAlphanumeric($length);
        return $this;
    }
    
    public function alphanumericRange($minLength = 0, $maxLength = 0): self {
        $this->handleAlphanumeric(null, $minLength, $maxLength);
        return $this;
    }
    
    
    public function textAndDigits($length = null): self {
        $this->handleAlphanumeric($length);
        return $this;
    }
    
    public function textAndDigitsRange($minLength = 0, $maxLength = 0): self {
        $this->handleAlphanumeric(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleAlphanumeric($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "[a-zA-Z0-9]";  // Matches alphanumeric characters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }
    
    public function whitespace($length = null): self {
        $this->handleWhitespace($length);
        return $this;
    }
    
    public function whitespaceRange($minLength = 0, $maxLength = 0): self {
        $this->handleWhitespace(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleWhitespace($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\s";  // Matches whitespace characters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function anyChar($length = null): self {
        $this->handleAnyChar($length);
        return $this;
    }
    
    public function anyCharRange($minLength = 0, $maxLength = 0): self {
        $this->handleAnyChar(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleAnyChar($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= ".";  // Matches any character (except newline)
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    // Character Classes END
}

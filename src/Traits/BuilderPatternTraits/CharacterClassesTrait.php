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

    public function numbers($length = null): self {
        $this->handleDigits($length);
        return $this;
    }
    
    public function numbersRange($minLength = 0, $maxLength = 0): self {
        $this->handleDigits(null, $minLength, $maxLength);
        return $this;
    }
    
    public function anyNumbers(): self {
        $this->handleDigits();
        return $this;
    }
    
    public function digit(): self {
        return $this->digits(1);  // Reuse the existing digits method
    }
    
    public function number(): self {
        return $this->digits(1);  // Reuse the existing digits method
    }
    
    private function handleDigits($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\d";  // Matches digits
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function nonDigits($length = null): self {
        $this->handleNonDigits($length);
        return $this;
    }

    public function nonDigit(): self {
        return $this->nonDigits(1);
    }

    public function nonDigitsRange($minLength = 0, $maxLength = 0): self {
        $this->handleNonDigits(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleNonDigits($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\D";  // Matches digits
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

    public function nonWhitespace($length = null): self {
        $this->handleNonWhitespace($length);
        return $this;
    }

    public function nonWhitespaceRange($minLength = 0, $maxLength = 0): self {
        $this->handleNonWhitespace(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleNonWhitespace($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\S";  // Matches whitespace characters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function wordChar(): self {
        return $this->wordChars(1);
    }

    public function wordChars($length = null): self {
        $this->handleWordChar($length);
        return $this;
    }

    public function wordCharRange($minLength = 0, $maxLength = 0): self {
        $this->handleWordChar(null, $minLength, $maxLength);
        return $this;
    }

    public function wordCharsRange($minLength = 0, $maxLength = 0): self {
        $this->handleWordChar(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleWordChar($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\w";  // Matches word characters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function nonWordChar(): self {
        $this->nonWordChars(1);
        return $this;
    }

    public function nonWordChars($length = null): self {
        $this->handleNonWordChar($length);
        return $this;
    }

    public function nonWordCharRange($minLength = 0, $maxLength = 0): self {
        $this->handleNonWordChar(null, $minLength, $maxLength);
        return $this;
    }
    
    private function handleNonWordChar($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\W";  // Matches non-word characters
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function anyChar(): self {
        return $this->anyChars(1);
    }

    public function anyChars($length = null): self {
        $this->handleAnyChar($length);
        return $this;
    }
    
    public function anyCharRange($minLength = 0, $maxLength = 0): self {
        $this->handleAnyChar(null, $minLength, $maxLength);
        return $this;
    }
    
    public function anyCharsRange($minLength = 0, $maxLength = 0): self {
        return $this->anyCharRange($minLength, $maxLength);
    }
    
    private function handleAnyChar($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= ".";  // Matches any character (except newline)
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function visibleChars($length = null): self {
        $this->handleVisibleChars($length);
        return $this;
    }

    public function visibleCharsRange($minLength = 0, $maxLength = 0): self {
        $this->handleVisibleChars(null, $minLength, $maxLength);
        return $this;
    }

    private function handleVisibleChars($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\S";  // Matches any non-whitespace character (visible characters)
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    public function invisibleChars($length = null): self {
        $this->handleInvisibleChars($length);
        return $this;
    }

    public function invisibleCharsRange($minLength = 0, $maxLength = 0): self {
        $this->handleInvisibleChars(null, $minLength, $maxLength);
        return $this;
    }

    private function handleInvisibleChars($length = null, $minLength = 0, $maxLength = 0) {
        $this->pattern .= "\\s";  // Matches any whitespace character (invisible characters)
        $this->pattern .= $this->getLengthOption($length, $minLength, $maxLength);
    }

    // Character Classes END
}

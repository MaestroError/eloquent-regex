<?php

namespace Maestroerror\EloquentRegex\Traits\BuilderPatternTraits;

trait SpecificCharsTrait {

    // Helper methods:

    private function handleExact(string|array $string, $caseSensitive = true, $quantifier = null) {
        if (is_array($string)) {
            $string = $this->escapeArray($string);
            $escapedString = "(" . implode("|", $string) . ")";
        } else {
            $escapedString = preg_quote($string, '/');
        }
        $pattern = $caseSensitive ? $escapedString : "(?i)" . $escapedString;
        $this->pattern .= $this->applyQuantifier($pattern, $quantifier);
        return $this;
    }

    private function escapeAndAdd(string $char, $quantifier = null): self {
        $escapedChar = preg_quote($char, '/');
        $this->pattern .= $quantifier ? $this->applyQuantifier($escapedChar, $quantifier) : $escapedChar;
        return $this;
    }

    private function escapeArray(array $arr) {
        return array_map(function ($item) {
            return preg_quote($item, '/');
        }, $arr);
    }

    // Exact string methods:

    public function exact(string|array $string, $caseSensitive = true, $quantifier = null): self {
        return $this->handleExact($string, $caseSensitive, $quantifier);
    }

    public function exactly(string|array $string, $caseSensitive = true, $quantifier = null): self {
        return $this->handleExact($string, $caseSensitive, $quantifier);
    }

    public function literal(string|array $string, $caseSensitive = true, $quantifier = null): self {
        return $this->handleExact($string, $caseSensitive, $quantifier);
    }
    
    public function character(string $char, $caseSensitive = true, $quantifier = null): self {
        return $this->handleExact($char, $caseSensitive, $quantifier);
    }
    
    public function char(string $char, $caseSensitive = true, $quantifier = null): self {
        return $this->handleExact($char, $caseSensitive, $quantifier);
    }

    // Specific Characters:
    
    public function tab(): self {
        $this->pattern .= "\\t"; // Matches a tab character
        return $this;
    }
    
    public function newLine(): self {
        $this->pattern .= "\\n"; // Matches a newline character
        return $this;
    }

    public function carriageReturn(): self {
        $this->pattern .= "\\r"; // Matches a carriage return character
        return $this;
    }
    
    public function verticalTab(): self {
        $this->pattern .= "\\v"; // Matches a vertical tab character
        return $this;
    }

    public function formFeed(): self {
        $this->pattern .= "\\f"; // Matches a form feed character
        return $this;
    }

    public function dash(string|null $q = null) {
        return $this->escapeAndAdd("-", $q);
    }

    public function dot(string|null $q = null): self {
        return $this->escapeAndAdd(".", $q); // Matches dot "." character
    }

    public function space(string|null $q = null) {
        return $this->escapeAndAdd(" ", $q);
    }

    public function backslash(string|null $q = null): self {
        return $this->escapeAndAdd("\\", $q);
    }

    public function forwardSlash(string|null $q = null): self {
        return $this->escapeAndAdd("/", $q);
    }

    public function slash(string|null $q = null): self {
        return $this->escapeAndAdd("/", $q);
    }

    public function doubleSlash(string|null $q = null): self {
        return $this->escapeAndAdd("//", $q);
    }

    public function underscore(string|null $q = null): self {
        return $this->escapeAndAdd("_", $q);
    }

    public function pipe(string|null $q = null): self {
        return $this->escapeAndAdd("|", $q);
    }

    public function ampersand(string|null $q = null): self {
        return $this->escapeAndAdd("&", $q);
    }

    public function asterisk(string|null $q = null): self {
        return $this->escapeAndAdd("*", $q);
    }

    public function plus(string|null $q = null): self {
        return $this->escapeAndAdd("+", $q);
    }

    public function questionMark(string|null $q = null): self {
        return $this->escapeAndAdd("?", $q);
    }

    public function atSign(string|null $q = null): self {
        return $this->escapeAndAdd("@", $q);
    }

    public function atSymbol(string|null $q = null): self {
        return $this->escapeAndAdd("@", $q);
    }
    
    public function exclamationMark(string|null $q = null): self {
        return $this->escapeAndAdd("!", $q);
    }

    public function period(string|null $q = null): self {
        return $this->escapeAndAdd(".", $q);
    }

    public function comma(string|null $q = null): self {
        return $this->escapeAndAdd(",", $q);
    }

    public function semicolon(string|null $q = null): self {
        return $this->escapeAndAdd(";", $q);
    }

    public function colon(string|null $q = null): self {
        return $this->escapeAndAdd(":", $q);
    }

    public function equalSign(string|null $q = null): self {
        return $this->escapeAndAdd("=", $q);
    }

    public function tilde(string|null $q = null): self {
        return $this->escapeAndAdd("~", $q);
    }

    public function hyphen(string|null $q = null): self {
        return $this->escapeAndAdd("-", $q);
    }

    public function minus(string|null $q = null): self {
        return $this->escapeAndAdd("-", $q);
    }

    public function doubleQuote(string|null $q = null): self {
        return $this->escapeAndAdd("\"", $q);
    }

    public function singleQuote(string|null $q = null): self {
        return $this->escapeAndAdd("'", $q);
    }

    public function percent(string|null $q = null): self {
        return $this->escapeAndAdd("%", $q);
    }

    public function dollar(string|null $q = null): self {
        return $this->escapeAndAdd("$", $q);
    }

    public function hash(string|null $q = null): self {
        return $this->escapeAndAdd("#", $q);
    }

    public function hashtag(string|null $q = null): self {
        return $this->escapeAndAdd("#", $q);
    }

    public function backtick(string|null $q = null): self {
        return $this->escapeAndAdd("`", $q);
    }

    public function caret(string|null $q = null): self {
        return $this->escapeAndAdd("^", $q);
    }

    public function unicode($code): self {
        $this->pattern .= "\\x{" . dechex($code) . "}";
        $this->addExpressionFlag("u");
        return $this;
    }

    // Methods for paired characters with separate open and close methods and an extra method with a boolean argument

    public function openSquareBracket(string|null $q = null): self {
        return $this->escapeAndAdd("[", $q);
    }

    public function closeSquareBracket(string|null $q = null): self {
        return $this->escapeAndAdd("]", $q);
    }

    public function squareBracket($isOpen = true): self {
        return $isOpen ? $this->openSquareBracket() : $this->closeSquareBracket();
    }

    public function openCurlyBrace(string|null $q = null): self {
        return $this->escapeAndAdd("{", $q);
    }

    public function closeCurlyBrace(string|null $q = null): self {
        return $this->escapeAndAdd("}", $q);
    }

    public function curlyBrace($isOpen = true): self {
        return $isOpen ? $this->openCurlyBrace() : $this->closeCurlyBrace();
    }

    public function openParenthesis(string|null $q = null): self {
        return $this->escapeAndAdd("(", $q);
    }

    public function closeParenthesis(string|null $q = null): self {
        return $this->escapeAndAdd(")", $q);
    }

    public function parenthesis($isOpen = true): self {
        return $isOpen ? $this->openParenthesis() : $this->closeParenthesis();
    }

    public function openAngleBracket(string|null $q = null): self {
        return $this->escapeAndAdd("<", $q);
    }

    public function closeAngleBracket(string|null $q = null): self {
        return $this->escapeAndAdd(">", $q);
    }

    public function angleBracket($isOpen = true): self {
        return $isOpen ? $this->openAngleBracket() : $this->closeAngleBracket();
    }

}

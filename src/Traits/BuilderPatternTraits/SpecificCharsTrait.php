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

    private function escapeAndAdd(string $char): self {
        $escapedChar = preg_quote($char, '/');
        $this->pattern .= $escapedChar;
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

    public function dash() {
        return $this->escapeAndAdd("-");
    }

    public function dot(): self {
        return $this->escapeAndAdd("."); // Matches dot "." character
    }

    public function space() {
        return $this->escapeAndAdd(" ");
    }

    public function backslash(): self {
        return $this->escapeAndAdd("\\");
    }

    public function forwardSlash(): self {
        return $this->escapeAndAdd("/");
    }

    public function slash(): self {
        return $this->escapeAndAdd("/");
    }

    public function doubleSlash(): self {
        return $this->escapeAndAdd("//");
    }

    public function underscore(): self {
        return $this->escapeAndAdd("_");
    }

    public function pipe(): self {
        return $this->escapeAndAdd("|");
    }

    public function ampersand(): self {
        return $this->escapeAndAdd("&");
    }

    public function asterisk(): self {
        return $this->escapeAndAdd("*");
    }

    public function plus(): self {
        return $this->escapeAndAdd("+");
    }

    public function questionMark(): self {
        return $this->escapeAndAdd("?");
    }

    public function atSign(): self {
        return $this->escapeAndAdd("@");
    }

    public function atSymbol(): self {
        return $this->escapeAndAdd("@");
    }
    
    public function exclamationMark(): self {
        return $this->escapeAndAdd("!");
    }

    public function period(): self {
        return $this->escapeAndAdd(".");
    }

    public function comma(): self {
        return $this->escapeAndAdd(",");
    }

    public function semicolon(): self {
        return $this->escapeAndAdd(";");
    }

    public function colon(): self {
        return $this->escapeAndAdd(":");
    }

    public function equalSign(): self {
        return $this->escapeAndAdd("=");
    }

    public function tilde(): self {
        return $this->escapeAndAdd("~");
    }

    public function hyphen(): self {
        return $this->escapeAndAdd("-");
    }

    public function minus(): self {
        return $this->escapeAndAdd("-");
    }

    public function doubleQuote(): self {
        return $this->escapeAndAdd("\"");
    }

    public function singleQuote(): self {
        return $this->escapeAndAdd("'");
    }

    public function percent(): self {
        return $this->escapeAndAdd("%");
    }

    public function dollar(): self {
        return $this->escapeAndAdd("$");
    }

    public function hash(): self {
        return $this->escapeAndAdd("#");
    }

    public function hashtag(): self {
        return $this->escapeAndAdd("#");
    }

    public function backtick(): self {
        return $this->escapeAndAdd("`");
    }

    public function caret(): self {
        return $this->escapeAndAdd("^");
    }

    public function unicode($code): self {
        $this->pattern .= "\\x{" . dechex($code) . "}";
        $this->addExpressionFlag("u");
        return $this;
    }

    // Methods for paired characters with separate open and close methods and an extra method with a boolean argument

    public function openSquareBracket(): self {
        return $this->escapeAndAdd("[");
    }

    public function closeSquareBracket(): self {
        return $this->escapeAndAdd("]");
    }

    public function squareBracket($isOpen = true): self {
        return $isOpen ? $this->openSquareBracket() : $this->closeSquareBracket();
    }

    public function openCurlyBrace(): self {
        return $this->escapeAndAdd("{");
    }

    public function closeCurlyBrace(): self {
        return $this->escapeAndAdd("}");
    }

    public function curlyBrace($isOpen = true): self {
        return $isOpen ? $this->openCurlyBrace() : $this->closeCurlyBrace();
    }

    public function openParenthesis(): self {
        return $this->escapeAndAdd("(");
    }

    public function closeParenthesis(): self {
        return $this->escapeAndAdd(")");
    }

    public function parenthesis($isOpen = true): self {
        return $isOpen ? $this->openParenthesis() : $this->closeParenthesis();
    }

    public function openAngleBracket(): self {
        return $this->escapeAndAdd("<");
    }

    public function closeAngleBracket(): self {
        return $this->escapeAndAdd(">");
    }

    public function angleBracket($isOpen = true): self {
        return $isOpen ? $this->openAngleBracket() : $this->closeAngleBracket();
    }

}

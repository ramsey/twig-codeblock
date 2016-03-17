<?php

namespace Ramsey\Twig\CodeBlock\Test\Mock;

class PygmentsMock
{
    public $pygmentizePath;
    public $code;
    public $lexer;
    public $format;
    public $options;

    public function __construct($pygmentizePath)
    {
        $this->pygmentizePath = $pygmentizePath;
    }

    public function highlight($code, $lexer, $format, $options)
    {
        $this->code = $code;
        $this->lexer = $lexer;
        $this->format = $format;
        $this->options = $options;

        return $this;
    }
}

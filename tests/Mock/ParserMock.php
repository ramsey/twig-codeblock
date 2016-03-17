<?php

namespace Ramsey\Twig\CodeBlock\Test\Mock;

class ParserMock extends \Twig_Parser
{
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    public function setExpressionParser($expressionParser)
    {
        $this->expressionParser = $expressionParser;
    }
}

<?php
namespace MiniQueryLanguage\Translators;

use MiniQueryLanguage\AstNodes\AbstractNode;

abstract class AbstractTranslator
{
    protected $ast;

    public function __construct(AbstractNode $ast)
    {
        $this->ast = $ast;
    }

    abstract public function translate(): string;
}

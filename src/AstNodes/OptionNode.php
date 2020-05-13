<?php
namespace MiniQueryLanguage\AstNodes;

use MiniQueryLanguage\Visitors\AbstractNodeVisitor;

class OptionNode extends AbstractNode
{
    protected $field;

    protected $value;

    public function __construct(string $field, string $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitOptionNode($this);
    }
}

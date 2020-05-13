<?php
namespace MiniQueryLanguage\AstNodes;

use MiniQueryLanguage\Visitors\AbstractNodeVisitor;

class OperatorNode extends AbstractNode
{
    protected $value;

    protected $children = [];

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function addChild(AbstractNode $node): void
    {
        $node->setParent($this);
        $this->children[] = $node;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitOperatorNode($this);
    }
}

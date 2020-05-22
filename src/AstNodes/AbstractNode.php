<?php
namespace MiniQueryLanguage\AstNodes;

use MiniQueryLanguage\Visitors\AbstractVisitor;

abstract class AbstractNode
{
    protected $parent;

    public function setParent(AbstractNode $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): AbstractNode
    {
        return $this->parent;
    }

    public function accept(AbstractVisitor $visitor)
    {
        $visitor->visit($this);
    }

    public function getNodeName(): string
    {
        $items = explode('\\', get_class($this));
        return end($items);
    }

    abstract public function toArray(): array;
}

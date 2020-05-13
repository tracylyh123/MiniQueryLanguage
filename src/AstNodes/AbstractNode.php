<?php
namespace MiniQueryLanguage\AstNodes;

use MiniQueryLanguage\Visitors\AbstractNodeVisitor;

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

    abstract public function accept(AbstractNodeVisitor $visitor);
}

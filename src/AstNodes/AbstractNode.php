<?php
namespace MiniQueryLanguage\AstNodes;

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
}

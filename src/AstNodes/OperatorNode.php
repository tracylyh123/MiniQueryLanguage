<?php
namespace MiniQueryLanguage\AstNodes;

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

    public function toArray(): array
    {
        $arr = [
            'name' => __CLASS__,
            'value' => $this->value,
        ];
        foreach ($this->children as $child) {
            $arr['children'][] = $child->toArray();
        }
        return $arr;
    }
}

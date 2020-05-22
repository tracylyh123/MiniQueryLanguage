<?php
namespace MiniQueryLanguage\AstNodes;

class OptionNode extends AbstractNode
{
    protected $field;

    protected $valueNode;

    public function __construct(string $field, AbstractValueNode $valueNode)
    {
        $this->field = $field;
        $this->valueNode = $valueNode;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): AbstractValueNode
    {
        return $this->valueNode;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getNodeName(),
            'field' => $this->field,
            'value' => $this->valueNode->toArray()
        ];
    }
}

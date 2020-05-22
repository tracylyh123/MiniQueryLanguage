<?php
namespace MiniQueryLanguage\AstNodes;

class RegularValueNode extends AbstractValueNode
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getNodeName(),
            'value' => $this->value
        ];
    }
}

<?php
namespace MiniQueryLanguage\AstNodes;

class RangeValueNode extends AbstractValueNode
{
    protected $left;

    protected $includeLeft;

    protected $right;

    protected $includeRight;

    public function __construct(
        string $left = null,
        bool $includeLeft,
        string $right = null,
        bool $includeRight
    ) {
        $this->left = $left;
        $this->includeLeft = $includeLeft;
        $this->right = $right;
        $this->includeRight = $includeRight;
    }

    public function getLeft(): ?string
    {
        return $this->left;
    }

    public function getRight(): ?string
    {
        return $this->right;
    }

    public function includeLeft(): bool
    {
        return $this->includeLeft;
    }

    public function includeRight(): bool
    {
        return $this->includeRight;
    }

    public function hasLeft(): bool
    {
        return isset($this->left);
    }

    public function hasRight(): bool
    {
        return isset($this->right);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getNodeName(),
            'left' => $this->left,
            'include_left' => $this->includeLeft,
            'right' => $this->right,
            'include_right' => $this->includeRight,
        ];
    }
}

<?php
namespace MiniQueryLanguage;

class Token
{
    protected $type;

    protected $value;

    protected $position;

    public function __construct(string $type, string $value, int $position)
    {
        $this->type = $type;
        $this->value = $value;
        $this->position = $position;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function typeIs(string $type): bool
    {
        return $type === $this->type;
    }
}

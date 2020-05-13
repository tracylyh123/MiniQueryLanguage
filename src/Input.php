<?php
namespace MiniQueryLanguage;

class Input implements \Iterator, \Countable
{
    protected $chars = [];

    protected $position = 0;

    public function __construct(string $input)
    {
        if (strlen($input) > 0) {
            $this->chars = str_split($input);
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): string
    {
        if (!isset($this->chars[$this->position])) {
            throw new \RuntimeException("invalid position {$this->position}, cannot get current char");
        }
        return $this->chars[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->chars[$this->position]);
    }

    public function count(): int
    {
        return count($this->chars);
    }
}

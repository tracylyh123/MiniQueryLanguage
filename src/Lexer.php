<?php
namespace MiniQueryLanguage;

class Lexer implements \Iterator
{
    const T_OPERATOR = 'T_OPERATOR';
    const T_IDENTIFIER = 'T_IDENTIFIER';
    const T_LITERAL = 'T_LITERAL';
    const T_LPAREN = 'T_LPAREN';
    const T_RPAREN = 'T_RPAREN';
    const T_UNKNOWN = 'T_UNKNOWN';
    const T_EOF = 'T_EOF';

    const OPERATORS = [
        'or',
        'and',
        'not'
    ];

    const PARENS = [
        '(' => self::T_LPAREN,
        ')' => self::T_RPAREN,
    ];

    const QUOTES = [
        '"',
        "'"
    ];

    const WHITESPACES = [
        ' ',
    ];

    const IDENTIFIERS = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
        'y', 'z', '_', '0', '1', '2', '3', '4', '5', '6',
        '7', '8', '9',
    ];

    protected $input;

    protected $tokens = [];

    protected $position = 0;

    protected $isFinished = false;

    public function __construct(Input $input)
    {
        $this->input = $input;
        $this->init();
    }

    protected function init(): void
    {
        $this->rewind();
        $this->tokens = [
            0 => $this->nextToken()
        ];
    }

    public function current(): Token
    {
        if (!isset($this->tokens[$this->position])) {
            throw new \RuntimeException("invalid position {$this->position}, cannot get current token");
        }
        return $this->tokens[$this->position];
    }

    public function next(): void
    {
        if (!isset($this->tokens[++$this->position]) && !$this->isFinished) {
            $token = $this->nextToken();
            if ($token->typeIs(self::T_EOF)) {
                $this->isFinished = true;
            }
            $this->tokens[$this->position] = $token;
        }
    }

    public function key(): int
    {
        return $this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
        $this->input->rewind();
        $this->isFinished = false;
    }

    public function valid(): bool
    {
        return !$this->isFinished;
    }

    protected function nextToken(): Token
    {
        bof:
        if (!$this->input->valid()) {
            $token = new Token(self::T_EOF, '', count($this->input));
        } else {
            $current = $this->input->current();
            $position = $this->input->key();

            if (in_array($current, self::IDENTIFIERS)) {
                $token = $this->consumeIdentifiers($current, $position);
            } elseif (in_array($current, self::QUOTES)) {
                $token = $this->consumeInside($current, $position);
            } elseif (isset(self::PARENS[$current])) {
                $this->input->next();
                $token = new Token(self::PARENS[$current], $current, $position);
            } elseif (in_array($current, self::WHITESPACES)) {
                $this->input->next();
                goto bof;
            } else {
                $this->input->next();
                $token = new Token(self::T_UNKNOWN, $current, $position);
            }
        }
        return $token;
    }

    protected function consumeIdentifiers(string $current, int $position): Token
    {
        $buffer = '';
        do {
            $buffer .= $current;
            $this->input->next();
            $current = $this->input->current();
        } while ($this->input->valid() && in_array($current, self::IDENTIFIERS));

        if ($current === ':') {
            $token = new Token(self::T_IDENTIFIER, $buffer, $position);
        } elseif ($current === ' ' && in_array($buffer, self::OPERATORS)) {
            $token = new Token(self::T_OPERATOR, $buffer, $position);
        } else {
            $token = new Token(self::T_UNKNOWN, $buffer, $position);
        }
        $this->input->next();
        return $token;
    }

    protected function consumeInside(string $delimiter, int $position): Token
    {
        $this->input->next();
        $buffer = '';
        while ($this->input->current() !== $delimiter) {
            if ($this->input->current() === '\\') {
                $buffer .= '\\';
                $this->input->next();
            }
            if (!$this->input->valid()) {
                return new Token(self::T_UNKNOWN, $buffer, $position);
            }
            $buffer .= $this->input->current();
            $this->input->next();
        }
        $this->input->next();
        return new Token(self::T_LITERAL, $buffer, $position);
    }
}

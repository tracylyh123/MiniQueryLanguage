<?php
namespace MiniQueryLanguage;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\AstNodes\RangeValueNode;
use MiniQueryLanguage\AstNodes\RegularValueNode;

class Parser
{
    protected $lexer;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse(): AbstractNode
    {
        $token = $this->lexer->current();
        if ($token->typeIs(Lexer::T_LPAREN)) {
            $node = $this->parseExpression();
        } elseif ($token->typeIs(Lexer::T_IDENTIFIER)) {
            $node = $this->parseOption();
        } elseif ($token->typeIs(Lexer::T_UNKNOWN)) {
            throw new \RuntimeException("unknown token {$token->getValue()}, position: {$token->getPosition()}");
        } else {
            throw new \RuntimeException("unexpected token type: {$token->getType()}");
        }
        return $node;
    }

    protected function parseRange(): RangeValueNode
    {
        $left = $right = $includeLeft = $includeRight = null;
        $current = $this->lexer->current();
        if ($current->typeIs(Lexer::T_LBRACE)) {
            $includeLeft = false;
        } elseif ($current->typeIs(Lexer::T_LBRACKET)) {
            $includeLeft = true;
        } else {
            throw new \RuntimeException("unexpected token type: {$current->getType()}");
        }
        $this->lexer->next();
        $current = $this->lexer->current();
        if ($current->typeIs(Lexer::T_LITERAL)) {
            $left = $current->getValue();
            $this->lexer->next();
        }
        $current = $this->lexer->current();
        if (!$current->typeIs(Lexer::T_COMMA)) {
            throw new \RuntimeException("unexpected token type: {$current->getType()}");
        }
        $this->lexer->next();
        $current = $this->lexer->current();
        if ($current->typeIs(Lexer::T_LITERAL)) {
            $right = $current->getValue();
            $this->lexer->next();
        }
        $current = $this->lexer->current();
        if ($current->typeIs(Lexer::T_RBRACE)) {
            $includeRight = false;
        } elseif ($current->typeIs(Lexer::T_RBRACKET)) {
            $includeRight = true;
        } else {
            throw new \RuntimeException("unexpected token type: {$current->getType()}");
        }
        return new RangeValueNode($left, $includeLeft, $right, $includeRight);
    }

    protected function parseOption(): AbstractNode
    {
        $current = $this->lexer->current();
        $this->lexer->next();
        $next = $this->lexer->current();
        if ($next->typeIs(Lexer::T_LBRACKET) || $next->typeIs(Lexer::T_LBRACE)) {
            $value = $this->parseRange();
        } elseif ($next->typeIs(Lexer::T_LITERAL)) {
            $value = new RegularValueNode($next->getValue());
        } else {
            throw new \RuntimeException("unexpected token type: {$next->getType()}");
        }
        $node = new OptionNode($current->getValue(), $value);
        $this->lexer->next();
        return $node;
    }

    protected function parseExpression(): AbstractNode
    {
        $this->lexer->next();
        $token = $this->lexer->current();
        if (!$token->typeIs(Lexer::T_OPERATOR)) {
            throw new \RuntimeException("unexpected token type: {$token->getType()}");
        }
        $node = new OperatorNode($token->getValue());
        $this->lexer->next();

        while ($this->lexer->valid()) {
            $token = $this->lexer->current();
            if (!$token->typeIs(Lexer::T_RPAREN)) {
                $node->addChild($this->parse());
            } else {
                $this->lexer->next();
                break;
            }
        }
        return $node;
    }
}

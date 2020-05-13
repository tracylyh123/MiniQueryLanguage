<?php
namespace MiniQueryLanguage;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\Visitors\AbstractNodeVisitor;

class Parser
{
    protected $lexer;

    protected $visitor;

    public function __construct(Lexer $lexer, AbstractNodeVisitor $visitor = null)
    {
        $this->lexer = $lexer;
        $this->visitor = $visitor;
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

    protected function parseOption(): AbstractNode
    {
        $current = $this->lexer->current();
        $this->lexer->next();
        $next = $this->lexer->current();
        if (!$next->typeIs(Lexer::T_LITERAL)) {
            throw new \RuntimeException("unexpected token type: {$next->getType()}");
        }
        $this->lexer->next();
        $node = new OptionNode($current->getValue(), $next->getValue());
        if (isset($this->visitor)) {
            $this->visitor->visitOptionNode($node);
        }
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
        if (isset($this->visitor)) {
            $this->visitor->visitOperatorNode($node);
        }
        $this->lexer->next();

        while ($this->lexer->valid()) {
            $token = $this->lexer->current();
            if (!$token->typeIs(Lexer::T_RPAREN)) {
                $node->addChild($this->parse());
            } else {
                $this->lexer->next();
            }
        }
        return $node;
    }
}

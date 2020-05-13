<?php
namespace MiniQueryLanguage\Translators;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;

class SqlStatementTranslator extends AbstractTranslator
{
    public function translate(): string
    {
        return $this->_translate($this->ast);
    }

    public function _translate(AbstractNode $node): string
    {
        if ($node instanceof OperatorNode) {
            $expressions = [];
            foreach ($node->getChildren() as $child) {
                $expression = $this->_translate($child);
                $operator = $node->getValue() === 'not' ? '!=' : '=';
                $expressions[] = sprintf($expression, $operator);
            }
            return sprintf("(%s)", implode(' ' . $node->getValue() . ' ', $expressions));
        } elseif ($node instanceof OptionNode) {
            return sprintf("`%s`%%s'%s'", $node->getField(), $node->getValue());
        }
        throw new \RuntimeException('unexpected node type: ' . get_class($node));
    }
}

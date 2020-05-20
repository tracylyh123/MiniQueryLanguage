<?php
namespace MiniQueryLanguage\Translators;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\AstNodes\RangeValueNode;
use MiniQueryLanguage\AstNodes\RegularValueNode;

class SqlStatementTranslator extends AbstractTranslator
{
    public function translate(): string
    {
        return $this->_translate($this->ast);
    }

    protected function _translate(AbstractNode $node): string
    {
        if ($node instanceof OperatorNode) {
            $expressions = [];
            foreach ($node->getChildren() as $child) {
                $expressions[] = $this->_translate($child);
            }
            if ($node->getValue() === 'not') {
                return sprintf("!(%s)", implode(' and ', $expressions));
            }
            return sprintf("(%s)", implode(" {$node->getValue()} ", $expressions));
        } elseif ($node instanceof OptionNode) {
            $value = $node->getValue();
            if ($value instanceof RangeValueNode) {
                $clause = '';
                if ($value->hasLeft()) {
                    $clause .= sprintf("`%s`%s'%s'",
                        $node->getField(),
                        $value->includeLeft() ? '>=' : '>',
                        $value->getLeft()
                    );
                }
                if ($value->hasRight()) {
                    if (!empty($clause)) {
                        $clause .= ' and ';
                    }
                    $clause .= sprintf("`%s`%s'%s'",
                        $node->getField(),
                        $value->includeRight() ? '<=' : '<',
                        $value->getRight()
                    );
                }
                return $clause;
            } elseif ($value instanceof RegularValueNode) {
                return sprintf("`%s`%s'%s'",
                    $node->getField(),
                    '=',
                    $value->getValue()
                );
            }
        }
        throw new \RuntimeException('unexpected node type: ' . get_class($node));
    }
}

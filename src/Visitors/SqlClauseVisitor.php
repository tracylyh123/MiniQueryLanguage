<?php
namespace MiniQueryLanguage\Visitors;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\AstNodes\RangeValueNode;
use MiniQueryLanguage\AstNodes\RegularValueNode;

class SqlClauseVisitor extends AbstractVisitor
{
    protected $sqlClause = '';

    public function visitOperator(OperatorNode $node)
    {
        if ($node->getValue() === 'not') {
            $this->sqlClause .= "!(";
        } else {
            $this->sqlClause .= "(";
        }
        $children = $node->getChildren();
        while (($child = array_shift($children)) instanceof AbstractNode) {
            $this->visit($child);
            if (!empty($children)) {
                if ($node->getValue() === 'not') {
                    $this->sqlClause .= " and ";
                } else {
                    $this->sqlClause .= " {$node->getValue()} ";
                }
            }
        }
        $this->sqlClause .= ")";
    }

    public function visitOption(OptionNode $node)
    {
        $this->visit($node->getValue());
        $this->sqlClause = sprintf($this->sqlClause, $node->getField(), $node->getField());
    }

    public function visitRangeValue(RangeValueNode $node)
    {
        $clause = '';
        if ($node->hasLeft()) {
            $clause .= sprintf("`%%s`%s'%s'",
                $node->includeLeft() ? '>=' : '>',
                $node->getLeft()
            );
        }
        if ($node->hasRight()) {
            if (!empty($clause)) {
                $clause .= ' and ';
            }
            $clause .= sprintf("`%%s`%s'%s'",
                $node->includeRight() ? '<=' : '<',
                $node->getRight()
            );
        }
        $this->sqlClause .= $clause;
    }

    public function visitRegularValue(RegularValueNode $node)
    {
        $this->sqlClause .= sprintf("`%%s`%s'%s'",
            '=',
            $node->getValue()
        );
    }

    public function getSqlClause(): string
    {
        return $this->sqlClause;
    }
}

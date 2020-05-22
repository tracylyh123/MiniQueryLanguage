<?php
namespace MiniQueryLanguage\Visitors;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\AstNodes\RangeValueNode;
use MiniQueryLanguage\AstNodes\RegularValueNode;

abstract class AbstractVisitor
{
    // implements double dispatch
    public function visit(AbstractNode $node)
    {
        if ($node instanceof OperatorNode) {
            $this->visitOperator($node);
        } elseif ($node instanceof OptionNode) {
            $this->visitOption($node);
        } elseif ($node instanceof RangeValueNode) {
            $this->visitRangeValue($node);
        } elseif ($node instanceof RegularValueNode) {
            $this->visitRegularValue($node);
        } else {
            throw new \RuntimeException('unexpected node type: ' . get_class($node));
        }
    }

    abstract public function visitOperator(OperatorNode $node);

    abstract public function visitOption(OptionNode $node);

    abstract public function visitRangeValue(RangeValueNode $node);

    abstract public function visitRegularValue(RegularValueNode $node);
}

<?php
namespace MiniQueryLanguage\Visitors;

use MiniQueryLanguage\AstNodes\AbstractNode;
use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;
use MiniQueryLanguage\AstNodes\RangeValueNode;
use MiniQueryLanguage\AstNodes\RegularValueNode;

class TreePrintVisitor extends AbstractVisitor
{
    protected $tree = '';

    protected $indent = '';

    public function visitOperator(OperatorNode $node)
    {
        $children = $node->getChildren();
        $this->writeln("name: {$node->getNodeName()}, value: {$node->getValue()}")->indent();
        while (($child = array_shift($children)) instanceof AbstractNode) {
            $child->accept($this);
        }
        $this->outdent();
    }

    public function visitOption(OptionNode $node)
    {
        $this->writeln("name: {$node->getNodeName()}, value: {$node->getField()}");
        $node->getValue()->accept($this);
    }

    public function visitRegularValue(RegularValueNode $node)
    {
        $this->writeln("name: {$node->getNodeName()}, value: {$node->getValue()}");
    }

    public function visitRangeValue(RangeValueNode $node)
    {
        $this->writeln("name: {$node->getNodeName()}, left: {$node->getLeft()}, right: {$node->getRight()}");
    }

    public function __toString()
    {
        return $this->tree;
    }

    protected function writeln(string $line): TreePrintVisitor
    {
        $this->tree .= ($this->indent . $line . "\n");
        return $this;
    }

    protected function indent(): TreePrintVisitor
    {
        $this->indent .= "\t";
        return $this;
    }

    protected function outdent(): TreePrintVisitor
    {
        $this->indent = substr($this->indent, 0, -1);
        return $this;
    }
}

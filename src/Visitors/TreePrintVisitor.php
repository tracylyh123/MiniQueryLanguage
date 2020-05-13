<?php
namespace MiniQueryLanguage\Visitors;

use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;

class TreePrintVisitor extends AbstractNodeVisitor
{
    protected $source = '';

    protected $indent = '';

    public function visitOperatorNode(OperatorNode $node)
    {
        $this->indent()->writeln(sprintf('operator => %s', $node->getValue()));
    }

    public function visitOptionNode(OptionNode $node)
    {
        $this->writeln(sprintf('field => %s, value => %s', $node->getField(), $node->getValue()));
    }

    protected function indent(): TreePrintVisitor
    {
        $this->indent .= "\t";
        return $this;
    }

    protected function writeln(string $string): TreePrintVisitor
    {
        $this->source .= $this->indent . $string . "\n";
        return $this;
    }

    public function __toString()
    {
        return $this->source;
    }
}
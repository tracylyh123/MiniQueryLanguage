<?php
namespace MiniQueryLanguage\Visitors;

use MiniQueryLanguage\AstNodes\OperatorNode;
use MiniQueryLanguage\AstNodes\OptionNode;

abstract class AbstractNodeVisitor
{
    abstract public function visitOperatorNode(OperatorNode $node);

    abstract public function visitOptionNode(OptionNode $node);
}

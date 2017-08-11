<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

final class MergeExtractedMethodVisitor extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof ClassMethod) {
            $node->name = $node->name.'Extracted';
        }

        //TODO: Find, remove, in afterTraverse merge original method getstmts array with new one without the method ololol
//        if ($node instanceof StaticCall && $node->name === $this->destinationMethodNode->name && $node->class->getFirst() === 'parent') {
//            $node = $this->destinationMethodNode->getStmts();
//        }

        return $node;
    }
}
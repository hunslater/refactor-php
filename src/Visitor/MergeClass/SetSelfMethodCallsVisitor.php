<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor\MergeClass;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

final class SetSelfMethodCallsVisitor extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    private $extractedMethods;

    /**
     * SetExtractedMethodsVisitor constructor.
     * @param array $extractedMethods
     */
    public function __construct(array $extractedMethods)
    {
        $this->extractedMethods = $extractedMethods;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof ClassMethod && in_array(strtolower($node->name), $this->extractedMethods)) {
            $node->name = $node->name.'Extracted';
        } elseif (
            $node instanceof StaticCall &&
            $node->class->getFirst() === 'static' &&
            in_array(strtolower($node->name), $this->extractedMethods)
        ) {
            $args = $node->args;
            $node = new Node\Expr\StaticCall(
                new Node\Name('static'),
                $node->name.'Extracted'
            );
            $node->args = $args;
        }

        return $node;
    }
}
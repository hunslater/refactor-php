<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor\MergeClass;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeVisitorAbstract;

final class SetParentMethodCallsVisitor extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    private $extractedMethods;
    /**
     * @var array
     */
    private $destinationMethods;

    /**
     * SetExtractedMethodsVisitor constructor.
     * @param array $extractedMethods
     * @param array $destinationMethods
     */
    public function __construct(array $extractedMethods, array $destinationMethods)
    {
        $this->extractedMethods = $extractedMethods;
        $this->destinationMethods = $destinationMethods;
    }

    public function leaveNode(Node $node)
    {
        if (
            $node instanceof StaticCall &&
            $node->class->getFirst() === 'parent' &&
            in_array(strtolower($node->name), $this->extractedMethods)
        ) {
            $node = new Node\Expr\MethodCall(
                new Node\Expr\Variable('this'),
                $node->name.'Extracted'
            );
        } elseif (
            $node instanceof StaticCall &&
            $node->class->getFirst() === 'parent' &&
            in_array(strtolower($node->name), $this->destinationMethods)
        ) {
            $node = new Node\Expr\MethodCall(
                new Node\Expr\Variable('this'),
                $node->name
            );
        }

        return $node;
    }
}
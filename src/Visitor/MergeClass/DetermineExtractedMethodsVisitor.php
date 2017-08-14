<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor\MergeClass;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeVisitorAbstract;

final class DetermineExtractedMethodsVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $extractedMethods = [];

    /**
     * @var array
     */
    private $destinationMethods;

    /**
     * DetermineExtractedMethodsVisitor constructor.
     * @param array $destinationMethods
     */
    public function __construct(array $destinationMethods)
    {
        $this->destinationMethods = $destinationMethods;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @return array
     */
    public function getExtractedMethods(): array
    {
        return $this->extractedMethods;
    }

    public function leaveNode(Node $node)
    {
        if (
            $node instanceof StaticCall &&
            $node->class->getFirst() === 'parent' &&
            strtolower($node->name) === $this->methodName &&
            in_array(strtolower($node->name), $this->destinationMethods)
        ) {
            $this->extractedMethods[] = $this->methodName;
//            $node = new Node\Expr\MethodCall(
//                new Node\Expr\Variable('this'),
//                $node->name.'Extracted'
//            );
        }

        return $node;
    }
}
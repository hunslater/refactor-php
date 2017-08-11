<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeVisitorAbstract;
use RefactorPhp\ClassDescription;

final class MergeUniqueMethodVisitor extends NodeVisitorAbstract
{
    /**
     * @var ClassDescription
     */
    private $sourceClass;

    /**
     * @var ClassDescription
     */
    private $destinationClass;

    /**
     * MergeUniqueMethodVisitor constructor.
     * @param ClassDescription $sourceClass
     * @param ClassDescription $destinationClass
     */
    public function __construct(ClassDescription $sourceClass, ClassDescription $destinationClass)
    {
        $this->sourceClass = $sourceClass;
        $this->destinationClass = $destinationClass;
    }

    public function leaveNode(Node $node)
    {
//        dump($node);
        if ($node instanceof StaticCall && $node->class->getFirst() === 'parent') {
            dump($node);
        }

        return $node;
    }
}
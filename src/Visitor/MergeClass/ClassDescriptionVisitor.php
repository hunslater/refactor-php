<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor\MergeClass;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use RefactorPhp\ClassDescription;

final class ClassDescriptionVisitor extends NodeVisitorAbstract
{
    /**
     * @var ClassDescription
     */
    private $classDescription;

    /**
     * ClassNodesVisitor constructor.
     * @param ClassDescription $classDescription
     */
    public function __construct(ClassDescription $classDescription)
    {
        $this->classDescription = $classDescription;
    }

    /**
     * @param Node $node
     * @return bool|Node
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->classDescription->setName($node->name);
            $this->classDescription->setExtends($node->extends);
            foreach ($node->implements as $interface) {
                $this->classDescription->addImplements($interface);
            }
        } elseif ($node instanceof Node\Stmt\ClassMethod) {
            $this->classDescription->addMethod($node);
            return NodeTraverser::REMOVE_NODE;
        } elseif ($node instanceof Node\Stmt\Property) {
            $this->classDescription->addProperty($node);
            return NodeTraverser::REMOVE_NODE;
        } elseif ($node instanceof Node\Stmt\ClassConst) {
            $this->classDescription->addConstant($node);
            return NodeTraverser::REMOVE_NODE;
        }

        return $node;
    }

    /**
     * @return ClassDescription
     */
    public function getClassDescription(): ClassDescription
    {
        return $this->classDescription;
    }
}
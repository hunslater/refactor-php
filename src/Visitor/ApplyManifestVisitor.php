<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\NodeManifestInterface;

final class ApplyManifestVisitor extends NodeVisitorAbstract
{
    /**
     * @var FindAndReplaceInterface
     */
    private $manifest;

    /**
     * ApplyManifestVisitor constructor.
     * @param FindAndReplaceInterface $manifest
     */
    public function __construct(FindAndReplaceInterface $manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * @param Node $node
     * @return int|Node
     */
    public function leaveNode(Node $node)
    {
        if ($this->manifest->getNodeCondition($node)) {
            return $this->manifest->getNodeReplacement($node);
        }

        return $node;
    }
}
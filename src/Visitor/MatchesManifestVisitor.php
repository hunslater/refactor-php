<?php
declare(strict_types=1);

namespace RefactorPhp\Visitor;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use RefactorPhp\Manifest\NodeManifestInterface;

final class MatchesManifestVisitor extends NodeVisitorAbstract
{
    /**
     * @var NodeManifestInterface
     */
    private $manifest;

    /**
     * MatchesManifestVisitor constructor.
     * @param NodeManifestInterface $manifest
     */
    public function __construct(NodeManifestInterface $manifest)
    {
        $this->manifest = $manifest;
    }

    /**
     * @param Node $node
     * @return int|Node
     */
    public function enterNode(Node $node)
    {
        if ($this->manifest->getNodeCondition($node)) {
            return NodeTraverser::STOP_TRAVERSAL;
        }

        return $node;
    }
}
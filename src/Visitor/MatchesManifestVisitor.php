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
     * Manifest matches file nodes.
     */
    const MANIFEST_MATCHED = 4;

    /**
     * @var NodeManifestInterface
     */
    private $manifest;

    /**
     * @var bool
     */
    protected $isMatch = false;

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
            $this->isMatch = true;
            return NodeTraverser::STOP_TRAVERSAL;
        }

        return $node;
    }

    /**
     * @param array $nodes
     * @return array|int
     */
    public function afterTraverse(array $nodes)
    {
        return $this->isMatch === true ? self::MANIFEST_MATCHED : $nodes;
    }
}
<?php
namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface FindAndReplaceInterface extends NodeManifestInterface
{
    /**
     * @param Node $node
     * @return Node|int
     */
    public function getNodeReplacement(Node $node);
}
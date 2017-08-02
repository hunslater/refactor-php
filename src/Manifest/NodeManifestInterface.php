<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use PhpParser\Node;

interface NodeManifestInterface extends ManifestInterface
{
    /**
     * Condition for nodes to match active manifest.
     *
     * @param Node $node
     * @return bool
     */
    public function getNodeCondition(Node $node): bool;
}
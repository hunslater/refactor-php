<?php
declare(strict_types=1);

namespace RefactorPhp\Manifest;

use PhpParser\Node;
use RefactorPhp\Finder;

interface NodeManifestInterface extends ManifestInterface
{
    /**
     * Condition for nodes to match active manifest.
     *
     * @param Node $node
     * @return bool
     */
    public function getNodeCondition(Node $node): bool;

    /**
     * @return Finder
     */
    public function getFinder(): Finder;
}
<?php
declare(strict_types=1);

namespace RefactorPhp\Node;

use PhpParser\Node;
use RefactorPhp\Manifest\FindAndReplaceInterface;
use RefactorPhp\Manifest\NodeManifestInterface;
use Symfony\Component\Finder\SplFileInfo;

interface NodeParserInterface
{
    /**
     * Returns traversed list of nodes with relationships to each other.
     *
     * @param SplFileInfo $file
     * @param bool $withRelationships
     * @return array
     */
    public function getFileNodes(SplFileInfo $file, bool $withRelationships = true): array;

    /**
     * Checks if nodes match provided manifest.
     *
     * @param Node[] $nodes Array of nodes
     * @param NodeManifestInterface $manifest
     * @return bool
     */
    public function matchesManifest(array $nodes, NodeManifestInterface $manifest): bool;

    /**
     * Applies manifest replaces to nodes.
     *
     * @param Node[] $nodes Array of nodes
     * @param FindAndReplaceInterface $manifest
     * @return array
     */
    public function applyManifest(array $nodes, FindAndReplaceInterface $manifest): array;
}